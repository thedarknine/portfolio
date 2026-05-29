<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProjectRepositoryTest extends KernelTestCase
{
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager;
    private ProjectRepository $projectRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager     = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->projectRepository = $this->entityManager->getRepository(Project::class);

        $this->cleanUpDatabase();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->cleanUpDatabase();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    /**
     * Test the getProjects method returns projects sorted by year descending.
     */
    public function testGetProjectsOrdersByYearDescending(): void
    {
        // 1. Insert 3 projects in random chronological order
        $projectIntermediaire = (new Project())
            ->setName('Refonte Portfolio v3')
            ->setSlug('portfolio-v3')
            ->setYear(2022)
            ->setPeriod('2022')
            ->setDescription('Description du projet intermédiaire')
            ->setCategory('Web')
            ->setLogo('logo-v3.png');

        $projectAncien = (new Project())
            ->setName('Ancien Site Pro')
            ->setSlug('ancien-site')
            ->setYear(2015)
            ->setPeriod('2015')
            ->setDescription('Description du projet ancien')
            ->setCategory('Web')
            ->setLogo('logo-ancien.png');

        $projectRecent = (new Project())
            ->setName('Plateforme Symfony v4')
            ->setSlug('plateforme-symfony-v4')
            ->setYear(2026)
            ->setPeriod('2026')
            ->setDescription('Description du projet récent')
            ->setCategory('R&D')
            ->setLogo('logo-symfony.png');

        $this->entityManager->persist($projectIntermediaire);
        $this->entityManager->persist($projectAncien);
        $this->entityManager->persist($projectRecent);
        $this->entityManager->flush();

        // 2. Call the custom method
        $results = $this->projectRepository->getProjects();

        // 3. Assertions
        $this->assertCount(3, $results);

        // Validation of DESC sorting (2026 -> 2022 -> 2015)
        $this->assertSame($projectRecent, $results[0], 'The most recent project (2026) must be displayed first.');
        $this->assertSame($projectIntermediaire, $results[1], 'The project from 2022 must be in second position.');
        $this->assertSame($projectAncien, $results[2], 'The oldest project (2015) must be last.');
    }

    /**
     * Clean up the project table.
     */
    private function cleanUpDatabase(): void
    {
        $connection = $this->entityManager->getConnection();

        // If you have a join table (e.g., project_technology or project_image),
        // you'll need to clear it JUST BEFORE the project table to avoid FK conflicts.
        // $connection->executeStatement('DELETE FROM project_technology');

        $connection->executeStatement('DELETE FROM project');
        $this->entityManager->clear();
    }
}
