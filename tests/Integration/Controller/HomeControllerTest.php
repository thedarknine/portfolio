<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Controller;

use App\Entity\PageInfo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client        = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine.orm.entity_manager');

        $projectDir = static::getContainer()->getParameter('kernel.project_dir');

        $this->createRequiredPages();
    }

    /**
     * Test that homepage is successful.
     */
    public function testHomepageIsSuccessful(): void
    {
        // Execute a GET request on the homepage URL
        $this->client->request('GET', '/');

        // Assert that the HTTP response is a success (2xx)
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('div.main');
    }

    public function testIndexPageIsSuccessful(): void
    {
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test admin zone is protected and redirects anonymous users.
     */
    public function testAdminZoneIsProtected(): void
    {
        $this->client->request('GET', '/admin');

        // Check anonymous user is redirected (usually to /login)
        $this->assertResponseRedirects();
    }

    /**
     * Create on the fly the minimal structures in your test database
     * to prevent controller loops from crashing.
     */
    private function createRequiredPages(): void
    {
        // Example to force a page in the navigation list if your test database is empty:
        $pageRepo = $this->entityManager->getRepository(PageInfo::class);
        if (null === $pageRepo->findOneBy(['technicalName' => 'experience'])) {
            $page = (new PageInfo())
                ->setTitle('Expérience')
                ->setTechnicalName('experience')
                ->setSlug('experience')
                ->setTagline('Construire')
                ->setPublished(true);
            $this->entityManager->persist($page);
            $this->entityManager->flush();
        }
    }
}
