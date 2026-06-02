<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\PhotoType;
use App\Repository\PhotoTypeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PhotoTypeRepositoryTest extends KernelTestCase
{
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager;
    private PhotoTypeRepository $photoTypeRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager       = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->photoTypeRepository = $this->entityManager->getRepository(PhotoType::class);

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
     * Test the getPhotoTypes method returns photo types sorted by position ascending.
     */
    public function testGetPhotoTypesOrdersByPositionAscending(): void
    {
        // Insert photo types in random order to test the QueryBuilder
        $type2 = (new PhotoType())->setName('Portraits')->setSlug('portraits')->setPosition(2);
        $type1 = (new PhotoType())->setName('Paysages')->setSlug('paysages')->setPosition(1);
        $type3 = (new PhotoType())->setName('Macro')->setSlug('macro')->setPosition(3);

        $this->entityManager->persist($type2);
        $this->entityManager->persist($type1);
        $this->entityManager->persist($type3);
        $this->entityManager->flush();

        // Call the custom method
        $results = $this->photoTypeRepository->getPhotoTypes();

        // Assertions
        $this->assertCount(3, $results);
        $this->assertSame($type1, $results[0], 'The category in position 1 must be first.');
        $this->assertSame($type2, $results[1], 'The category in position 2 must be second.');
        $this->assertSame($type3, $results[2], 'The category in position 3 must be third.');
    }

    /**
     * Clean up the photo_type table.
     */
    private function cleanUpDatabase(): void
    {
        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('DELETE FROM photo_type');
        $this->entityManager->clear();
    }
}
