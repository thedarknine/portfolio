<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\PageInfo;
use App\Enum\PageCategory;
use App\Repository\PageInfoRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PageInfoRepositoryTest extends KernelTestCase
{
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager;
    private PageInfoRepository $pageInfoRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager      = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->pageInfoRepository = $this->entityManager->getRepository(PageInfo::class);

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
     * Test that findAllAsArray returns sorted arrays by position.
     */
    public function testFindAllAsArrayReturnsSortedArrays(): void
    {
        // 1. Insert 3 pages in random order by position
        $pageArcade = (new PageInfo())
            ->setTitle('Arcade')
            ->setTechnicalName('arcade')
            ->setSlug('arcade')
            ->setTagline('Mes bornes')
            ->setSubtitle('Mes bornes')
            ->setQuote('Mes bornes')
            ->setPosition(2)
            ->setInHeader(true)
            ->setCategory(PageCategory::INTEREST);

        $pagePhotos = (new PageInfo())
            ->setTitle('Photos')
            ->setTechnicalName('photos')
            ->setSlug('photos')
            ->setTagline('Mes fragments de lumière')
            ->setSubtitle('Mes fragments de lumière')
            ->setQuote('Mes fragments de lumière')
            ->setPosition(3)
            ->setInHeader(false)
            ->setCategory(PageCategory::INTEREST);

        $pageProjets = (new PageInfo())
            ->setTitle('Projets')
            ->setTechnicalName('projets')
            ->setSlug('projets')
            ->setTagline('Mes réalisations')
            ->setSubtitle('Mes réalisations')
            ->setQuote('Mes réalisations')
            ->setPosition(1)
            ->setInHeader(true)
            ->setCategory(PageCategory::CAREER);

        $this->entityManager->persist($pageArcade);
        $this->entityManager->persist($pagePhotos);
        $this->entityManager->persist($pageProjets);
        $this->entityManager->flush();

        // 2. Call the method under test
        $results = $this->pageInfoRepository->findAllAsArray();

        // 3. Assertions
        $this->assertCount(3, $results);

        // Verify that the results are arrays and not objects
        $this->assertIsArray($results[0], 'The result of getArrayResult should be a raw array');

        // Validate the ASC order based on position (Projets [pos 1] -> Arcade [pos 2] -> Photos [pos 3])
        $this->assertSame('projets', $results[0]['slug'], 'The page "Projets" (position 1) should be first.');
        $this->assertSame('arcade', $results[1]['slug'], 'The page "Arcade" (position 2) should be second.');
        $this->assertSame('photos', $results[2]['slug'], 'The page "Photos" (position 3) should be third.');

        // Optional: Ensure the expected entity keys are present
        $this->assertArrayHasKey('title', $results[0]);
        $this->assertArrayHasKey('tagline', $results[0]);
    }

    /**
     * Clean up the page_info table.
     */
    private function cleanUpDatabase(): void
    {
        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('DELETE FROM page');
        $this->entityManager->clear();
    }
}
