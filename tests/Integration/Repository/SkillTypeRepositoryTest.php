<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\SkillType;
use App\Repository\SkillTypeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SkillTypeRepositoryTest extends KernelTestCase
{
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager;
    private SkillTypeRepository $skillTypeRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->skillTypeRepository = $this->entityManager->getRepository(SkillType::class);

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
     * Test that getSkillTypes filters deleted items and orders by position.
     */
    public function testGetSkillTypesFiltersDeletedAndOrdersByPosition(): void
    {
        // 1. Insert valid types in random position order
        $type2 = (new SkillType())
            ->setName('Front-end')
            ->setSlug('front-end')
            ->setLogo('front-end.png')
            ->setPosition(2)
            ->setDeleted(false);
        $type1 = (new SkillType())
            ->setName('Back-end')
            ->setSlug('back-end')
            ->setLogo('back-end.png')
            ->setPosition(1)
            ->setDeleted(false);

        // 2. Insert a type marked as deleted (it should not be returned)
        $typeDeleted = (new SkillType())
            ->setName('Ancien truc')
            ->setSlug('ancien-truc')
            ->setLogo('ancien-truc.png')
            ->setPosition(0)
            ->setDeleted(true);

        $this->entityManager->persist($type2);
        $this->entityManager->persist($type1);
        $this->entityManager->persist($typeDeleted);
        $this->entityManager->flush();

        // 3. Call the method
        $results = $this->skillTypeRepository->getSkillTypes();

        // 4. Assertions
        // Only 2 results expected (the deleted one should be filtered)
        $this->assertCount(2, $results);

        // Check ASC order (Back-end [pos 1] -> Front-end [pos 2])
        $this->assertSame($type1, $results[0], 'The type with position 1 should be first.');
        $this->assertSame($type2, $results[1], 'The type with position 2 should be second.');
    }

    /**
     * Clean up the skill_type table.
     */
    private function cleanUpDatabase(): void
    {
        $connection = $this->entityManager->getConnection();

        // Since the 'skill' table depends on 'skill_type', first clear 'skill'
        // to avoid foreign key constraint violations if there are any residuals.
        $connection->executeStatement('DELETE FROM skill');
        $connection->executeStatement('DELETE FROM skill_type');

        $this->entityManager->clear();
    }
}
