<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:generate-fixtures',
    description: 'Generates ID-independent fixtures with automatic ordered dependencies, imports, and ManyToMany relations.',
)]
class GenerateFixturesCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private KernelInterface $kernel,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'entityFqcn',
            InputArgument::OPTIONAL,
            'The Full Qualified Class Name of the entity (optional). If empty, processes all entities.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $entityFqcn = $input->getArgument('entityFqcn');

        $targetEntities = [];

        if ($entityFqcn) {
            if (!class_exists($entityFqcn)) {
                $io->error(sprintf('Entity "%s" does not exist.', $entityFqcn));

                return Command::FAILURE;
            }
            $targetEntities[] = $entityFqcn;
        } else {
            $allMetadata = $this->em->getMetadataFactory()->getAllMetadata();
            foreach ($allMetadata as $meta) {
                $targetEntities[] = $meta->getName();
            }
        }

        // Map existing target classes and their matching fixture shortnames
        $availableFixtureClasses = [];
        $shortNamesMapping = [];
        foreach ($targetEntities as $fqcn) {
            $reflect = new \ReflectionClass($fqcn);
            $availableFixtureClasses[$fqcn] = $reflect->getShortName().'AutoFixture';
            $shortNamesMapping[$fqcn] = $reflect->getShortName();
        }

        foreach ($targetEntities as $fqcn) {
            $reflect = new \ReflectionClass($fqcn);
            $shortName = $reflect->getShortName();

            $records = $this->em->getRepository($fqcn)->findAll();
            if (empty($records)) {
                $io->text(sprintf('ℹ️ %s: No data found in database, skipping.', $shortName));
                continue;
            }

            $metadata = $this->em->getClassMetadata($fqcn);
            $fieldNames = $metadata->getFieldNames();
            $associationNames = $metadata->getAssociationNames();

            $detectedDependencies = [];
            $entitiesToImport = [$fqcn]; // Start by importing the current entity class

            $fixtureClassName = $shortName.'AutoFixture';

            // --- COLLECT DEPENDENCIES AND ENTITY IMPORTS ---
            foreach ($associationNames as $assocName) {
                $targetClass = $metadata->getAssociationTargetClass($assocName);

                // Only handle relation if target entity is part of our current export scope
                if (isset($availableFixtureClasses[$targetClass])) {
                    $isOwningSide = $metadata->associationMappings[$assocName]['isOwningSide'] ?? false;

                    // Import and depend if it is a single association OR the owning side of a ManyToMany
                    if ($metadata->isSingleValuedAssociation($assocName) || $isOwningSide) {
                        $entitiesToImport[] = $targetClass;
                        if ($targetClass !== $fqcn) {
                            $detectedDependencies[] = '            '.$availableFixtureClasses[$targetClass].'::class,';
                        }
                    }
                }
            }

            // Deduplicate imports and structural dependencies
            $entitiesToImport = array_unique($entitiesToImport);
            $detectedDependencies = array_unique($detectedDependencies);

            // --- BUILD FIXTURE FILE HEADER ---
            $code = "<?php\n\nnamespace App\DataFixtures;\n\n";

            foreach ($entitiesToImport as $importFqcn) {
                $code .= "use $importFqcn;\n";
            }

            $code .= "use Doctrine\Bundle\FixturesBundle\Fixture;\n";
            if (!empty($detectedDependencies)) {
                $code .= "use Doctrine\Common\DataFixtures\DependentFixtureInterface;\n";
            }
            $code .= "use Doctrine\Persistence\ObjectManager;\n\n";

            if (!empty($detectedDependencies)) {
                $code .= "class $fixtureClassName extends Fixture implements DependentFixtureInterface\n{\n";
            } else {
                $code .= "class $fixtureClassName extends Fixture\n{\n";
            }

            $code .= "    public function load(ObjectManager \$manager): void\n    {\n";

            // --- GENERATE METHOD BODY ---
            foreach ($records as $index => $record) {
                $variableName = '$'.lcfirst($shortName).$index;
                $code .= "        $variableName = new $shortName();\n";

                $currentIdValues = $metadata->getIdentifierValues($record);
                $currentId = current($currentIdValues);

                // 1. Regular scalar fields
                foreach ($fieldNames as $fieldName) {
                    if ($metadata->isIdentifier($fieldName)) {
                        continue;
                    }

                    $getter = 'get'.ucfirst($fieldName);
                    $booleanGetter = 'is'.ucfirst($fieldName); // Standard Symfony naming pattern for booleans
                    $setter = 'set'.ucfirst($fieldName);

                    // Choose the right getter method depending on availability
                    $chosenGetter = null;
                    if (method_exists($record, $getter)) {
                        $chosenGetter = $getter;
                    } elseif (method_exists($record, $booleanGetter)) {
                        $chosenGetter = $booleanGetter;
                    }

                    if (null !== $chosenGetter && method_exists($record, $setter)) {
                        $value = $record->$chosenGetter();

                        if (is_null($value)) {
                            $formattedValue = 'null';
                        } elseif (is_bool($value)) {
                            $formattedValue = $value ? 'true' : 'false';
                        } elseif (is_numeric($value)) {
                            $formattedValue = $value;
                        } elseif ($value instanceof \DateTimeInterface) {
                            $formattedValue = "new \\DateTime('".$value->format('Y-m-d H:i:s')."')";
                        } else {
                            $formattedValue = var_export($value, true);
                        }

                        $code .= "        {$variableName}->{$setter}($formattedValue);\n";
                    }
                }

                // 2. Single-valued relations (ManyToOne / OneToOne)
                foreach ($associationNames as $assocName) {
                    $getter = 'get'.ucfirst($assocName);
                    $setter = 'set'.ucfirst($assocName);

                    if ($metadata->isSingleValuedAssociation($assocName) && method_exists($record, $getter) && method_exists($record, $setter)) {
                        $associatedObject = $record->$getter();

                        if (null !== $associatedObject) {
                            $targetClass = $metadata->getAssociationTargetClass($assocName);
                            $targetShortName = $shortNamesMapping[$targetClass] ?? 'Entity';

                            $associatedMetadata = $this->em->getClassMetadata($targetClass);
                            $associatedIdValues = $associatedMetadata->getIdentifierValues($associatedObject);
                            $associatedId = current($associatedIdValues);

                            $referenceKey = lcfirst($targetShortName).'_'.$associatedId;

                            $code .= "        try {\n";
                            $code .= "            {$variableName}->{$setter}(\$this->getReference('$referenceKey', {$targetShortName}::class));\n";
                            $code .= "        } catch (\\OutOfBoundsException \$e) {\n";
                            $code .= "            // Reference does not exist yet or target entity was skipped\n";
                            $code .= "        }\n";
                        }
                    }
                }

                // 3. Collection-valued relations (ManyToMany) - Owning Side only
                foreach ($associationNames as $assocName) {
                    $getter = 'get'.ucfirst($assocName);
                    $adder = 'add'.ucfirst(rtrim($assocName, 's')); // e.g., getSkills -> addSkill

                    $isOwningSide = $metadata->associationMappings[$assocName]['isOwningSide'] ?? false;

                    if ($metadata->isCollectionValuedAssociation($assocName) && $isOwningSide) {
                        if (method_exists($record, $getter) && method_exists($record, $adder)) {
                            $associatedCollection = $record->$getter();

                            foreach ($associatedCollection as $associatedObject) {
                                $targetClass = $metadata->getAssociationTargetClass($assocName);
                                $targetShortName = $shortNamesMapping[$targetClass] ?? 'Entity';

                                $associatedMetadata = $this->em->getClassMetadata($targetClass);
                                $associatedIdValues = $associatedMetadata->getIdentifierValues($associatedObject);
                                $associatedId = current($associatedIdValues);

                                $referenceKey = lcfirst($targetShortName).'_'.$associatedId;

                                $code .= "        try {\n";
                                $code .= "            {$variableName}->{$adder}(\$this->getReference('$referenceKey', {$targetShortName}::class));\n";
                                $code .= "        } catch (\\OutOfBoundsException \$e) {\n";
                                $code .= "            // Reference target does not exist yet\n";
                                $code .= "        }\n";
                            }
                        }
                    }
                }

                $code .= "        \$manager->persist($variableName);\n";
                $currentRefKey = lcfirst($shortName).'_'.$currentId;
                $code .= "        \$this->addReference('$currentRefKey', $variableName);\n\n";
            }

            $code .= "        \$manager->flush();\n    }\n";

            // --- GENERATE DEPENDENCIES METHOD AT THE BOTTOM ---
            if (!empty($detectedDependencies)) {
                $code .= "\n    /**\n     * @return array<int, class-string<Fixture>>\n     */\n";
                $code .= "    public function getDependencies(): array\n    {\n";
                $code .= "        return [\n".implode("\n", $detectedDependencies)."\n        ];\n    }\n";
            }

            $code .= "}\n";

            $fixturePath = $this->kernel->getProjectDir()."/src/DataFixtures/$fixtureClassName.php";
            file_put_contents($fixturePath, $code);

            $io->text(sprintf('✅ Generated fixture: <info>%s.php</info> %s', $fixtureClassName, !empty($detectedDependencies) ? '(with explicit dependencies)' : ''));
        }

        $io->success('All automated fixtures re-generated successfully with English comments!');

        return Command::SUCCESS;
    }
}
