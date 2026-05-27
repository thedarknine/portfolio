<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Command;

use App\Entity\Company;
use App\Entity\Experience;
use App\Entity\Project;
use App\Entity\Skill;
use App\Entity\SkillType;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateFixturesCommandTest extends KernelTestCase
{
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager;
    private string $companyFixturePath;
    private string $experienceFixturePath;
    private string $projectFixturePath;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getContainer()->get('doctrine.orm.entity_manager');

        $projectDir = self::getContainer()->getParameter('kernel.project_dir');
        $this->companyFixturePath = $projectDir.'/src/DataFixtures/CompanyAutoFixture.php';
        $this->experienceFixturePath = $projectDir.'/src/DataFixtures/ExperienceAutoFixture.php';
        $this->projectFixturePath = $projectDir.'/src/DataFixtures/ProjectAutoFixture.php';

        $this->cleanUpDatabaseAndFiles();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->cleanUpDatabaseAndFiles();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    /**
     * Test error case when entity does not exist.
     */
    public function testExecuteFailsIfEntityDoesNotExist(): void
    {
        $application = new Application(self::$kernel);
        $command = $application->find('app:generate-fixtures');
        $commandTester = new CommandTester($command);

        $statusCode = $commandTester->execute([
            'entityFqcn' => 'App\Entity\FakeEntity',
        ]);

        $this->assertSame(1, $statusCode);
        $this->assertStringContainsString('Entity "App\Entity\FakeEntity" does not exist.', $commandTester->getDisplay());
    }

    /**
     * Test generate fixture with dependencies (ManyToOne).
     * Covers the block of detection of associations and getDependencies().
     */
    public function testExecuteGeneratesFixtureWithDependencies(): void
    {
        // 1. Create the relational structure in the database: Company -> Experience
        $company = (new Company())
            ->setName('Atelier Clay')
            ->setSlug('atelier-clay')
            ->setLogo('logo.png')
            ->setCity('Orléans')
            ->setDepartment(45);
        $this->entityManager->persist($company);

        // Adjust the setters according to the actual properties of your Experience entity (e.g.: title, description, active...)
        $experience = (new Experience())
            ->setTitle('Web Dev')
            ->setSlug('web-dev')
            ->setDescription('Description')
            ->setCompany($company)
            ->setStartDate(new \DateTime('2020-01-01'))
            ->setEndDate(new \DateTime('2021-01-01'));

        $this->entityManager->persist($experience);
        $this->entityManager->flush();

        // 2. Run the global command without arguments so that Company AND Experience are in the scope ($availableFixtureClasses)
        $application = new Application(self::$kernel);
        $command = $application->find('app:generate-fixtures');
        $commandTester = new CommandTester($command);

        $statusCode = $commandTester->execute([]); // Process all entities

        // 3. Assertions console
        $this->assertSame(0, $statusCode);

        // 4. Validate the ExperienceAutoFixture.php file (which holds the relation)
        $this->assertFileExists($this->experienceFixturePath);
        $fileContent = file_get_contents($this->experienceFixturePath);

        // The Company entity must be imported ("use App\Entity\Company;")
        $this->assertStringContainsString('use App\Entity\Company;', $fileContent, 'The target entity namespace must be imported.');

        // Class must implement DependentFixtureInterface
        $this->assertStringContainsString('implements DependentFixtureInterface', $fileContent, 'The class must implement the dependencies interface.');

        // The getDependencies method must be generated and contain CompanyAutoFixture
        $this->assertStringContainsString('function getDependencies()', $fileContent);
        $this->assertStringContainsString('CompanyAutoFixture::class', $fileContent, 'CompanyAutoFixture must be listed in the dependencies.');
    }

    /**
     * Test generate fixture with ManyToMany relations (Owning Side).
     */
    public function testExecuteGeneratesManyToManyRelations(): void
    {
        // 1. Create the entities required for the ManyToMany (Project <-> Skill)
        $type = (new SkillType())
            ->setName('Back')
            ->setSlug('back')
            ->setLogo('back.png')
            ->setDeleted(false);
        $this->entityManager->persist($type);

        $skill = (new Skill())
            ->setName('Symfony')
            ->setSlug('symfony')
            ->setLogo('symfony.png')
            ->setPosition(1)
            ->setSkillType($type)
            ->setStartYear(2020)
            ->setLevel(5)
            ->setDisplay(true);
        $this->entityManager->persist($skill);

        $project = (new Project())
            ->setName('Mon Super Portfolio')
            ->setSlug('mon-super-portfolio')
            ->setLogo('logo.png')
            ->setDescription('Description du projet')
            ->setYear(2026)
            ->setPeriod('6 mois')
            ->setCategory('Web');

        // 2. Create the Company and Experience that own the relation
        $company = (new Company())
            ->setName('Atelier Clay')
            ->setSlug('atelier-clay')
            ->setLogo('atelier-clay.png')
            ->setCity('Orléans')
            ->setDepartment(45);
        $this->entityManager->persist($company);

        $experience = (new Experience())
            ->setCompany($company)
            ->setTitle('Web Developer')
            ->setSlug('web-developer')
            ->setStartDate(new \DateTime('2020-01-01'))
            ->setEndDate(new \DateTime('2025-01-01'))
            ->setDescription('Description de l\'expérience');

        $experience->addSkill($skill);

        $this->entityManager->persist($experience);
        $this->entityManager->flush();

        // 3. Run the global command
        $application = new Application(self::$kernel);
        $command = $application->find('app:generate-fixtures');
        $commandTester = new CommandTester($command);

        $statusCode = $commandTester->execute([]);

        $this->assertSame(0, $statusCode);
        $this->assertFileExists($this->experienceFixturePath);

        // 4. Inspect the generated file for the experience
        $fileContent = file_get_contents($this->experienceFixturePath);

        // Check the presence of the ManyToMany collection adder and its try/catch
        $this->assertStringContainsString('->addSkill($this->getReference', $fileContent, 'The generated code must contain the ManyToMany adder call.');
        $this->assertStringContainsString('catch (\OutOfBoundsException $e)', $fileContent, 'The security try/catch block must surround the adder.');
    }

    /**
     * Test behavior when a valid existing entity is provided as an argument.
     */
    public function testExecuteProcessesOnlySingleValidEntityProvidedAsArgument(): void
    {
        // 1. Insert a company so the command skips to the next entity (Company is first in the list)
        $company = (new Company())
            ->setName('Atelier Terre')
            ->setSlug('atelier-terre')
            ->setLogo('logo.png')
            ->setCity('Orléans')
            ->setDepartment(45);
        $this->entityManager->persist($company);
        $this->entityManager->flush();

        // 2. Run the command with the 'entityFqcn' argument
        $application = new Application(self::$kernel);
        $command = $application->find('app:generate-fixtures');
        $commandTester = new CommandTester($command);

        $statusCode = $commandTester->execute([
            'entityFqcn' => Company::class,
        ]);

        // 3. Assertions
        $this->assertSame(0, $statusCode);
        $this->assertStringContainsString('✅ Generated fixture: CompanyAutoFixture.php', $commandTester->getDisplay());
        $this->assertFileExists($this->companyFixturePath);

        // Ensure the User fixture was NOT created (proof that we filtered on Company only)
        $projectDir = self::getContainer()->getParameter('kernel.project_dir');
        $userFixturePath = $projectDir.'/src/DataFixtures/UserAutoFixture.php';
        $this->assertFileDoesNotExist($userFixturePath, 'The command should only process the entity provided as an argument.');
    }

    /**
     * Clean up database and delete generated fixtures.
     */
    private function cleanUpDatabaseAndFiles(): void
    {
        $connection = $this->entityManager->getConnection();

        // Ordered database purge
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeStatement('DELETE FROM project');
        $connection->executeStatement('DELETE FROM skill');
        $connection->executeStatement('DELETE FROM skill_type');
        $connection->executeStatement('DELETE FROM experience_item');
        $connection->executeStatement('DELETE FROM experience_link');
        $connection->executeStatement('DELETE FROM experience');
        $connection->executeStatement('DELETE FROM company');
        $connection->executeStatement('DELETE FROM user');
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');

        // Purge physical files
        $projectDir = self::getContainer()->getParameter('kernel.project_dir');
        $fixturesToClean = [
            $this->companyFixturePath,
            $this->experienceFixturePath,
            $this->projectFixturePath,
            $projectDir.'/src/DataFixtures/UserAutoFixture.php',
            $projectDir.'/src/DataFixtures/SkillAutoFixture.php',
            $projectDir.'/src/DataFixtures/SkillTypeAutoFixture.php',
        ];

        foreach ($fixturesToClean as $path) {
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->entityManager->clear();
    }
}
