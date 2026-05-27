<?php

namespace App\Tests\Command;

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
        
        // 🎯 MAJ : Les fichiers générés par le test iront maintenant dans le sous-dossier /Testing
        $this->companyFixturePath = $projectDir.'/src/DataFixtures/Testing/Unit/CompanyAutoFixture.php';
        $this->experienceFixturePath = $projectDir.'/src/DataFixtures/Testing/Unit/ExperienceAutoFixture.php';
        $this->projectFixturePath = $projectDir.'/src/DataFixtures/Testing/Unit/ProjectAutoFixture.php';

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
            '--group' => 'testing_unit', 
        ]);

        $this->assertSame(1, $statusCode);
        $this->assertStringContainsString('Entity "App\Entity\FakeEntity" does not exist.', $commandTester->getDisplay());
    }

    /**
     * Test generate fixture with dependencies (ManyToOne).
     */
    public function testExecuteGeneratesFixtureWithDependencies(): void
    {
        $company = (new Company())->setName('Atelier Clay')->setSlug('atelier-clay')->setLogo('logo.png')->setCity('Orléans')->setDepartment(45);
        $this->entityManager->persist($company);

        $experience = (new Experience())->setTitle('Web Dev')->setSlug('web-dev')->setDescription('Description')->setCompany($company)->setStartDate(new \DateTime('2020-01-01'))->setEndDate(new \DateTime('2021-01-01'));
        $this->entityManager->persist($experience);
        $this->entityManager->flush();

        $application = new Application(self::$kernel);
        $command = $application->find('app:generate-fixtures');
        $commandTester = new CommandTester($command);

        $statusCode = $commandTester->execute([
            '--group' => 'testing_unit',
        ]);

        $this->assertSame(0, $statusCode);

        $this->assertFileExists($this->experienceFixturePath);
        $fileContent = file_get_contents($this->experienceFixturePath);

        $this->assertStringContainsString('use App\Entity\Company;', $fileContent);
        $this->assertStringContainsString('implements DependentFixtureInterface, FixtureGroupInterface', $fileContent); // 🎯 MAJ assertion (les 2 interfaces)
        $this->assertStringContainsString('return [\'testing_unit\'];', $fileContent); // 🎯 On vérifie que le groupe généré est bien 'test'
        $this->assertStringContainsString('CompanyAutoFixture::class', $fileContent);
    }

    /**
     * Test generate fixture with ManyToMany relations (Owning Side).
     */
    public function testExecuteGeneratesManyToManyRelations(): void
    {
        $type = (new SkillType())->setName('Back')->setSlug('back')->setLogo('back.png')->setDeleted(false);
        $this->entityManager->persist($type);

        $skill = (new Skill())->setName('Symfony')->setSlug('symfony')->setLogo('symfony.png')->setPosition(1)->setSkillType($type)->setStartYear(2020)->setLevel(5)->setDisplay(true);
        $this->entityManager->persist($skill);

        $company = (new Company())->setName('Atelier Clay')->setSlug('atelier-clay')->setLogo('atelier-clay.png')->setCity('Orléans')->setDepartment(45);
        $this->entityManager->persist($company);

        $experience = (new Experience())->setCompany($company)->setTitle('Web Developer')->setSlug('web-developer')->setStartDate(new \DateTime('2020-01-01'))->setEndDate(new \DateTime('2025-01-01'))->setDescription('Description de l\'expérience');
        $experience->addSkill($skill);

        $this->entityManager->persist($experience);
        $this->entityManager->flush();

        $application = new Application(self::$kernel);
        $command = $application->find('app:generate-fixtures');
        $commandTester = new CommandTester($command);

        $statusCode = $commandTester->execute([
            '--group' => 'testing_unit',
        ]);

        $this->assertSame(0, $statusCode);
        $this->assertFileExists($this->experienceFixturePath);

        $fileContent = file_get_contents($this->experienceFixturePath);
        $this->assertStringContainsString('->addSkill($this->getReference', $fileContent);
        $this->assertStringContainsString('catch (\OutOfBoundsException $e)', $fileContent);
    }

    /**
     * Test behavior when a valid existing entity is provided as an argument.
     */
    public function testExecuteProcessesOnlySingleValidEntityProvidedAsArgument(): void
    {
        $company = (new Company())
            ->setName('Atelier Terre')
            ->setSlug('atelier-terre')
            ->setLogo('logo.png')
            ->setCity('Orléans')
            ->setDepartment(45);
        $this->entityManager->persist($company);
        $this->entityManager->flush();

        $application = new Application(self::$kernel);
        $command = $application->find('app:generate-fixtures');
        $commandTester = new CommandTester($command);

        // 🎯 MAJ : Argument + Option de groupe
        $statusCode = $commandTester->execute([
            'entityFqcn' => Company::class,
            '--group' => 'testing_unit',
        ]);

        $this->assertSame(0, $statusCode);
        // 🎯 MAJ assertion : le message console contient le sous-dossier désormais
        $this->assertStringContainsString('✅ Generated fixture: /src/DataFixtures/Testing/Unit/CompanyAutoFixture.php', $commandTester->getDisplay());
        $this->assertFileExists($this->companyFixturePath);

        $projectDir = self::getContainer()->getParameter('kernel.project_dir');
        $userFixturePath = $projectDir.'/src/DataFixtures/Testing/Unit/UserAutoFixture.php';
        $this->assertFileDoesNotExist($userFixturePath);
    }

    /**
     * Clean up database and delete generated fixtures.
     */
    private function cleanUpDatabaseAndFiles(): void
    {
        $connection = $this->entityManager->getConnection();

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

        $projectDir = self::getContainer()->getParameter('kernel.project_dir');
        
        // 🎯 MAJ : On nettoie dans /Testing/ pour laisser la racine intacte
        $fixturesToClean = [
            $this->companyFixturePath,
            $this->experienceFixturePath,
            $this->projectFixturePath,
            $projectDir.'/src/DataFixtures/Testing/Unit/UserAutoFixture.php',
            $projectDir.'/src/DataFixtures/Testing/Unit/SkillAutoFixture.php',
            $projectDir.'/src/DataFixtures/Testing/Unit/SkillTypeAutoFixture.php',
        ];

        foreach ($fixturesToClean as $path) {
            if (file_exists($path)) {
                unlink($path);
            }
        }

        // On nettoie proprement le dossier unitaire
        $testingDir = $projectDir.'/src/DataFixtures/Testing/Unit';
        if (is_dir($testingDir) && count(scandir($testingDir)) === 2) {
            rmdir($testingDir);
        }

        $this->entityManager->clear();
    }
}