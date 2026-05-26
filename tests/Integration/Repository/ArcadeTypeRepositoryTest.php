<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\ArcadeType;
use App\Repository\ArcadeTypeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArcadeTypeRepositoryTest extends KernelTestCase
{
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager;
    private ArcadeTypeRepository $arcadeTypeRepository;

    protected function setUp(): void
    {
        // Boot the Symfony Kernel
        self::bootKernel();

        // Get the EntityManager and Repository from the Container
        $this->entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->arcadeTypeRepository = $this->entityManager->getRepository(ArcadeType::class);

        // Clean up the table before each test to avoid side effects
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
     * Test that getArcadeTypes returns elements correctly sorted by position.
     */
    public function testGetArcadeTypesOrdersByPositionAscending(): void
    {
        // Insert in random order to validate ASC sorting by QueryBuilder
        $arcade2 = (new ArcadeType())->setName('Bartop')->setSlug('bartop')->setPosition(2);
        $arcade1 = (new ArcadeType())->setName('Pincab')->setSlug('pincab')->setPosition(1);
        $arcade3 = (new ArcadeType())->setName('Cocktail')->setSlug('cocktail')->setPosition(3);

        $this->entityManager->persist($arcade2);
        $this->entityManager->persist($arcade1);
        $this->entityManager->persist($arcade3);
        $this->entityManager->flush();

        // Call the custom method
        $results = $this->arcadeTypeRepository->getArcadeTypes();

        // Assertions
        $this->assertCount(3, $results);
        $this->assertSame($arcade1, $results[0], 'The first element must be the one with position 1');
        $this->assertSame($arcade2, $results[1], 'The second element must be the one with position 2');
        $this->assertSame($arcade3, $results[2], 'The third element must be the one with position 3');
    }

    /**
     * Clean up the arcade_type table.
     */
    private function cleanUpDatabase(): void
    {
        $connection = $this->entityManager->getConnection();

        // Execute a raw TRUNCATE or DELETE statement to immediately close the statement
        $connection->executeStatement('DELETE FROM arcade_type');

        // Clear the EntityManager's internal entity cache
        $this->entityManager->clear();
    }
}
