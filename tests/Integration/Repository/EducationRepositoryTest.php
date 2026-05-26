<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\Education;
use App\Entity\School;
use App\Enum\EducationType;
use App\Repository\EducationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EducationRepositoryTest extends KernelTestCase
{
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager;
    private EducationRepository $educationRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->educationRepository = $this->entityManager->getRepository(Education::class);

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
     * Test that the method returns educations with their school, ordered by year descending.
     */
    public function testGetEducationsWithSchoolOrdersByYearDescending(): void
    {
        // 1. Create and persist schools first (foreign key constraint)
        $school1 = (new School())
            ->setName('Université d\'Orléans')
            ->setSlug('univ-orleans')
            ->setCity('Orléans')
            ->setDepartment(45)
            ->setLogo('orleans.jpeg');

        $school2 = (new School())
            ->setName('Autre École')
            ->setSlug('autre-ecole')
            ->setCity('Paris')
            ->setDepartment(75)
            ->setLogo('autre.jpeg');

        $this->entityManager->persist($school1);
        $this->entityManager->persist($school2);

        // 2. Create educations (in reverse chronological order)
        $educationAncien = (new Education())
            ->setTitle('DEUG MIAS')
            ->setSlug('deug-mias')
            ->setDetails('Mathématiques, informatique et applications aux sciences')
            ->setType(EducationType::UNIVERSITARY)
            ->setYear(2004)
            ->setSchool($school1);

        $educationRecent = (new Education())
            ->setTitle('Master Big Data / Web')
            ->setSlug('master-big-data-web')
            ->setDetails('Big Data et Web')
            ->setType(EducationType::PROFESSIONAL)
            ->setYear(2006)
            ->setSchool($school2);

        $this->entityManager->persist($educationAncien);
        $this->entityManager->persist($educationRecent);
        $this->entityManager->flush();

        // 3. Call the repository method
        $results = $this->educationRepository->getEducationsWithSchool();

        // 4. Assertions
        $this->assertCount(2, $results);

        // Verification of DESC order (2006 should come before 2004)
        $this->assertSame($educationRecent, $results[0], 'The most recent education (2006) should come first.');
        $this->assertSame($educationAncien, $results[1], 'The oldest education (2004) should come last.');

        // Verification of addSelect (the relation to the school must be initialized and correct)
        $this->assertSame('Autre École', $results[0]->getSchool()->getName());
        $this->assertSame('Université d\'Orléans', $results[1]->getSchool()->getName());
    }

    /**
     * Clean up the database tables in the correct order to respect foreign key constraints.
     */
    private function cleanUpDatabase(): void
    {
        $connection = $this->entityManager->getConnection();

        // Delete children (Education) first, then parents (School)
        $connection->executeStatement('DELETE FROM education');
        $connection->executeStatement('DELETE FROM school');

        $this->entityManager->clear();
    }
}
