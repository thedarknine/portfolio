<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\ResourceLink;
use App\Repository\ResourceLinkRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ResourceLinkRepositoryTest extends KernelTestCase
{
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager;
    private ResourceLinkRepository $resourceLinkRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->resourceLinkRepository = $this->entityManager->getRepository(ResourceLink::class);

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
     * Test the findAllAsArray method returns an associative array sorted by position.
     */
    public function testFindAllAsArrayReturnsSortedArrays(): void
    {
        // 1. Insert 3 resource links in random order
        $linkLinkedIn = (new ResourceLink())
            ->setTitle('LinkedIn')
            ->setSlug('linkedin')
            ->setUrl('https://linkedin.com/in/mon-profil')
            ->setIcon('fab fa-linkedin')
            ->setPosition(2)
            ->setInHero(true);

        $linkGitHub = (new ResourceLink())
            ->setTitle('GitHub')
            ->setSlug('github')
            ->setUrl('https://github.com/mon-pseudo')
            ->setIcon('fab fa-github')
            ->setPosition(1)
            ->setInHero(true);

        $linkAutre = (new ResourceLink())
            ->setTitle('Site Externe')
            ->setSlug('site-externe')
            ->setUrl('https://example.com')
            ->setIcon('fas fa-external-link-alt')
            ->setPosition(3)
            ->setInHero(false);

        $this->entityManager->persist($linkLinkedIn);
        $this->entityManager->persist($linkGitHub);
        $this->entityManager->persist($linkAutre);
        $this->entityManager->flush();

        // 2. Call the optimized method
        $results = $this->resourceLinkRepository->findAllAsArray();

        // 3. Assertions
        $this->assertCount(3, $results);

        // Validate that Doctrine did not hydrate objects (expected return : array)
        $this->assertIsArray($results[0], 'Each row must be an associative array.');

        // Check sorting ASC on the 'position' field (GitHub [1] -> LinkedIn [2] -> Site Externe [3])
        $this->assertSame('GitHub', $results[0]['title'], 'The link positioned at 1 (GitHub) must be first.');
        $this->assertSame('LinkedIn', $results[1]['title'], 'The link positioned at 2 (LinkedIn) must be second.');
        $this->assertSame('Site Externe', $results[2]['title'], 'The link positioned at 3 (Site Externe) must be last.');
    }

    /**
     * Clean up the resource_link table.
     */
    private function cleanUpDatabase(): void
    {
        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('DELETE FROM resource');
        $this->entityManager->clear();
    }
}
