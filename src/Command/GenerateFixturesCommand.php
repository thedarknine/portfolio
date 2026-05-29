<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
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
use Symfony\Component\Console\Input\InputOption;
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
            'The Full Qualified Class Name of the entity (optional). If empty, processes all entities.',
        )
            ->addOption(
                'group',
                'g',
                InputOption::VALUE_REQUIRED,
                'Doctrine group to assign to the generated fixture file.',
                'portfolio',
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io         = new SymfonyStyle($input, $output);
        $entityFqcn = $input->getArgument('entityFqcn');
        $group      = $input->getOption('group');

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
        $shortNamesMapping       = [];
        foreach ($targetEntities as $fqcn) {
            $reflect                        = new \ReflectionClass($fqcn);
            $availableFixtureClasses[$fqcn] = $reflect->getShortName() . 'AutoFixture';
            $shortNamesMapping[$fqcn]       = $reflect->getShortName();
        }

        foreach ($targetEntities as $fqcn) {
            $reflect   = new \ReflectionClass($fqcn);
            $shortName = $reflect->getShortName();

            $records = $this->em->getRepository($fqcn)->findAll();
            if (empty($records)) {
                $io->text(sprintf('ℹ️ %s: No data found in database, skipping.', $shortName));

                continue;
            }

            $metadata         = $this->em->getClassMetadata($fqcn);
            $fieldNames       = $metadata->getFieldNames();
            $associationNames = $metadata->getAssociationNames();

            $detectedDependencies = [];
            $entitiesToImport     = [$fqcn]; // Start by importing the current entity class

            $fixtureClassName = $shortName . 'AutoFixture';

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
                            $detectedDependencies[] = '            ' . $availableFixtureClasses[$targetClass] . '::class,';
                        }
                    }
                }
            }

            // Deduplicate imports and structural dependencies
            $entitiesToImport     = array_unique($entitiesToImport);
            $detectedDependencies = array_unique($detectedDependencies);

            if ('portfolio' === $group) {
                $namespace = 'App\DataFixtures';
                $subFolder = '/src/DataFixtures';
            } else {
                // If $group = 'test' -> App\DataFixtures\Testing
                // If $group = 'testing_unit' -> App\DataFixtures\Testing\Unit
                $subParts       = explode('_', $group);
                $formattedParts = array_map('ucfirst', $subParts); // ['Testing', 'Unit']

                // To match exactly with path
                if ('test' === $group) {
                    $namespace = 'App\DataFixtures\Testing';
                    $subFolder = '/src/DataFixtures/Testing';
                } else {
                    $namespace = 'App\DataFixtures\Testing\\' . implode('\\', array_slice($formattedParts, 1));
                    $subFolder = '/src/DataFixtures/Testing/' . implode('/', array_slice($formattedParts, 1));
                }
            }

            // --- BUILD FIXTURE FILE HEADER ---
            $code = "<?php\n\nnamespace $namespace;\n\n";

            foreach ($entitiesToImport as $importFqcn) {
                $code .= "use $importFqcn;\n";
            }

            $code .= "use Doctrine\Bundle\FixturesBundle\Fixture;\n";
            $code .= "use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;\n";
            if (!empty($detectedDependencies)) {
                $code .= "use Doctrine\Common\DataFixtures\DependentFixtureInterface;\n";
            }
            $code .= "use Doctrine\Persistence\ObjectManager;\n\n";

            if (!empty($detectedDependencies)) {
                $code .= "class $fixtureClassName extends Fixture implements DependentFixtureInterface, FixtureGroupInterface\n{\n";
            } else {
                $code .= "class $fixtureClassName extends Fixture implements FixtureGroupInterface\n{\n";
            }

            $code .= "    public function load(ObjectManager \$manager): void\n    {\n";

            // --- GENERATE METHOD BODY ---
            foreach ($records as $index => $record) {
                $variableName = '$' . lcfirst($shortName) . $index;
                $code .= "        $variableName = new $shortName();\n";

                $currentIdValues = $metadata->getIdentifierValues($record);
                $currentId       = current($currentIdValues);

                // 1. Regular scalar fields
                foreach ($fieldNames as $fieldName) {
                    if ($metadata->isIdentifier($fieldName)) {
                        continue;
                    }

                    $getter        = 'get' . ucfirst($fieldName);
                    $booleanGetter = 'is' . ucfirst($fieldName);
                    $setter        = 'set' . ucfirst($fieldName);

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
                            $formattedValue = "new \\DateTime('" . $value->format('Y-m-d H:i:s') . "')";
                        } else {
                            $formattedValue = var_export($value, true);
                        }

                        $code .= "        {$variableName}->{$setter}($formattedValue);\n";
                    }
                }

                // 2. Single-valued relations (ManyToOne / OneToOne)
                foreach ($associationNames as $assocName) {
                    $getter = 'get' . ucfirst($assocName);
                    $setter = 'set' . ucfirst($assocName);

                    if ($metadata->isSingleValuedAssociation($assocName) && method_exists($record, $getter) && method_exists($record, $setter)) {
                        $associatedObject = $record->$getter();

                        if (null !== $associatedObject) {
                            $targetClass     = $metadata->getAssociationTargetClass($assocName);
                            $targetShortName = $shortNamesMapping[$targetClass] ?? 'Entity';

                            $associatedMetadata = $this->em->getClassMetadata($targetClass);
                            $associatedIdValues = $associatedMetadata->getIdentifierValues($associatedObject);
                            $associatedId       = current($associatedIdValues);

                            $referenceKey = lcfirst($targetShortName) . '_' . $associatedId;

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
                    $getter = 'get' . ucfirst($assocName);
                    $adder  = 'add' . ucfirst(rtrim($assocName, 's'));

                    $isOwningSide = $metadata->associationMappings[$assocName]['isOwningSide'] ?? false;

                    if ($metadata->isCollectionValuedAssociation($assocName) && $isOwningSide) {
                        if (method_exists($record, $getter) && method_exists($record, $adder)) {
                            $associatedCollection = $record->$getter();

                            foreach ($associatedCollection as $associatedObject) {
                                $targetClass     = $metadata->getAssociationTargetClass($assocName);
                                $targetShortName = $shortNamesMapping[$targetClass] ?? 'Entity';

                                $associatedMetadata = $this->em->getClassMetadata($targetClass);
                                $associatedIdValues = $associatedMetadata->getIdentifierValues($associatedObject);
                                $associatedId       = current($associatedIdValues);

                                $referenceKey = lcfirst($targetShortName) . '_' . $associatedId;

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
                $currentRefKey = lcfirst($shortName) . '_' . $currentId;
                $code .= "        \$this->addReference('$currentRefKey', $variableName);\n\n";
            }

            $code .= "        \$manager->flush();\n    }\n";

            // --- GENERATE DEPENDENCIES METHOD AT THE BOTTOM ---
            if (!empty($detectedDependencies)) {
                $code .= "\n    /**\n     * @return array<int, class-string<Fixture>>\n     */\n";
                $code .= "    public function getDependencies(): array\n    {\n";
                $code .= "        return [\n" . implode("\n", $detectedDependencies) . "\n        ];\n    }\n";
            }

            // Use the real FixtureGroupInterface method
            $code .= "\n    public static function getGroups(): array\n    {\n        return ['$group'];\n    }\n";

            $code .= "}\n";

            // Dynamic folder structure based on group
            if ('portfolio' === $group) {
                $namespace = 'App\DataFixtures';
                $subFolder = '/src/DataFixtures';
            } else {
                // If $group = 'test' -> App\DataFixtures\Testing
                // If $group = 'testing_unit' -> App\DataFixtures\Testing\Unit
                $subParts       = explode('_', $group);
                $formattedParts = array_map('ucfirst', $subParts); // ['Testing', 'Unit']

                // Match exactly your expected path :
                if ('test' === $group) {
                    $namespace = 'App\DataFixtures\Testing';
                    $subFolder = '/src/DataFixtures/Testing';
                } elseif ('testing_unit' === $group) {
                    // Force the correct namespace and subfolder for unit tests
                    $namespace = 'App\DataFixtures\Testing\Unit';
                    $subFolder = '/src/DataFixtures/Testing/Unit';
                } else {
                    $namespace = 'App\DataFixtures\Testing\\' . implode('\\', array_slice($formattedParts, 1));
                    $subFolder = '/src/DataFixtures/Testing/' . implode('/', array_slice($formattedParts, 1));
                }
            }
            $directoryPath = $this->kernel->getProjectDir() . $subFolder;

            if (!is_dir($directoryPath)) {
                mkdir($directoryPath, 0755, true);
            }

            $fixturePath = $directoryPath . "/$fixtureClassName.php";
            file_put_contents($fixturePath, $code);

            $io->text(sprintf('✅ Generated fixture: <info>%s/%s.php</info> %s', $subFolder, $fixtureClassName, !empty($detectedDependencies) ? '(with explicit dependencies)' : ''));
        }

        $io->success('All automated fixtures re-generated successfully with English comments!');

        return Command::SUCCESS;
    }
}
