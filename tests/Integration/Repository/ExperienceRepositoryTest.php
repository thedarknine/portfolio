<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\Company;
use App\Entity\Experience;
use App\Repository\ExperienceRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ExperienceRepositoryTest extends KernelTestCase
{
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager;
    private ExperienceRepository $experienceRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->experienceRepository = $this->entityManager->getRepository(Experience::class);

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
     * Test that getExperiencesWithCompany applies the default limit (3)
     * and sorts by start date descending.
     */
    public function testGetExperiencesWithCompanyAppliesDefaultLimitAndOrder(): void
    {
        // 1. Create a test company
        $company = (new Company())
            ->setName('Tech Solutions')
            ->setSlug('tech-solutions')
            ->setLogo('company-logo.png')
            ->setCity('Orléans')
            ->setDepartment(45);

        $this->entityManager->persist($company);

        // 2. Insert 4 experiences spread over time (in reverse chronological order)
        $exp2010 = (new Experience())
            ->setTitle('Dev Mid')
            ->setSlug('dev-mid')
            ->setCompany($company)
            ->setStartDate(new \DateTime('2010-01-01'))
            ->setDescription('Description 2010');
        $exp2026 = (new Experience())
            ->setTitle('Product Owner')
            ->setSlug('product-owner')
            ->setCompany($company)
            ->setStartDate(new \DateTime('2026-01-01'))
            ->setDescription('Description 2026');
        $exp2006 = (new Experience())
            ->setTitle('Dev Junior')
            ->setSlug('dev-junior')
            ->setCompany($company)
            ->setStartDate(new \DateTime('2006-10-01'))
            ->setDescription('Description 2006');
        $exp2018 = (new Experience())
            ->setTitle('Dev Senior')
            ->setSlug('dev-senior')
            ->setCompany($company)
            ->setStartDate(new \DateTime('2018-06-01'))
            ->setDescription('Description 2018');

        $this->entityManager->persist($exp2010);
        $this->entityManager->persist($exp2026);
        $this->entityManager->persist($exp2006);
        $this->entityManager->persist($exp2018);
        $this->entityManager->flush();

        // 3. Call with the default limit (3)
        $resultsDefault = $this->experienceRepository->getExperiencesWithCompany();

        // Assertions on the limit and sorting (2026 -> 2018 -> 2010. 2006 should be excluded)
        $this->assertCount(3, $resultsDefault);
        $this->assertSame($exp2026, $resultsDefault[0], 'The most recent (2026) should be first.');
        $this->assertSame($exp2018, $resultsDefault[1]);
        $this->assertSame($exp2010, $resultsDefault[2], 'The third (2010) should be last.');

        // 4. Call with a custom limit to cover the flexibility of the argument
        $resultsCustom = $this->experienceRepository->getExperiencesWithCompany(2);
        $this->assertCount(2, $resultsCustom);
    }

    /**
     * Clean up tables to avoid foreign key violations.
     */
    private function cleanUpDatabase(): void
    {
        $connection = $this->entityManager->getConnection();

        $connection->executeStatement('DELETE FROM experience_item');
        $connection->executeStatement('DELETE FROM experience_link');
        $connection->executeStatement('DELETE FROM experience');
        $connection->executeStatement('DELETE FROM company');

        $this->entityManager->clear();
    }
}
