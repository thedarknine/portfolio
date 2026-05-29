<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\CreationType;
use App\Repository\CreationTypeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreationTypeRepositoryTest extends KernelTestCase
{
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager;
    private CreationTypeRepository $creationTypeRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager          = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->creationTypeRepository = $this->entityManager->getRepository(CreationType::class);

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
     * Test that getCreationTypes returns elements correctly sorted by position.
     * Test the QueryBuilder sorting by position ascending.
     */
    public function testGetCreationTypesOrdersByPositionAscending(): void
    {
        // Insert in random order to validate ASC sorting by QueryBuilder
        $creation2 = (new CreationType())->setName('Modelage')->setSlug('modelage')->setPosition(2);
        $creation1 = (new CreationType())->setName('Tournage')->setSlug('tournage')->setPosition(1);
        $creation3 = (new CreationType())->setName('Sculpture')->setSlug('sculpture')->setPosition(3);

        $this->entityManager->persist($creation2);
        $this->entityManager->persist($creation1);
        $this->entityManager->persist($creation3);
        $this->entityManager->flush();

        // Call the custom method
        $results = $this->creationTypeRepository->getCreationTypes();

        // Assertions
        $this->assertCount(3, $results);
        $this->assertSame($creation1, $results[0], 'Le premier élément doit être à la position 1');
        $this->assertSame($creation2, $results[1], 'The second element must be at position 2');
        $this->assertSame($creation3, $results[2], 'The third element must be at position 3');
    }

    /**
     * Clean up the creation_type table with a fast native query.
     */
    private function cleanUpDatabase(): void
    {
        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('DELETE FROM creation_type');
        $this->entityManager->clear();
    }
}
