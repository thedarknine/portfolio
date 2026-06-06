<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Controller;

use App\Entity\ArcadeType;
use App\Entity\CreationType;
use App\Entity\PageInfo;
use App\Entity\PhotoType;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;

class PortfolioControllerTest extends WebTestCase
{
    private $client;
    private EntityManagerInterface $entityManager;
    private Filesystem $filesystem;
    private string $publicImagesDir;

    protected function setUp(): void
    {
        $this->client        = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        $this->filesystem    = new Filesystem();

        $projectDir            = static::getContainer()->getParameter('kernel.project_dir');
        $this->publicImagesDir = $projectDir . '/public/images';

        $this->createRequiredPages();
        $this->setupFixturesForMediaPages();
    }

    protected function tearDown(): void
    {
        // Clean all temporary directories after each test
        $foldersToRemove = [
            $this->publicImagesDir . '/arcade/bartop',
            $this->publicImagesDir . '/creations/sculptures',
            $this->publicImagesDir . '/photos/paysages',
        ];

        foreach ($foldersToRemove as $folder) {
            if ($this->filesystem->exists($folder)) {
                $this->filesystem->remove($folder);
            }
        }

        parent::tearDown();
    }

    #[DataProvider('providePublicUrls')]
    public function testPublicPagesAreSuccessful(string $url): void
    {
        $this->client->request('GET', $url);

        // On vérifie simplement que la page répond un code HTTP 200 (OK)
        $this->assertResponseIsSuccessful();
    }

    public static function providePublicUrls(): \Generator
    {
        yield ['/experience'];
        yield ['/competences'];
        yield ['/formation'];
        yield ['/projets'];
        yield ['/photos'];
        yield ['/arcade'];
        yield ['/creations'];
    }

    public function testUnknownPageReturns404(): void
    {
        $this->client->request('GET', '/un-slug-qui-n-existe-pas');

        // On vérifie que la sécurité 404 fonctionne
        $this->assertResponseStatusCodeSame(404);
    }

    /**
     * Test admin zone is protected and redirects anonymous users.
     */
    public function testAdminZoneIsProtected(): void
    {
        $this->client->request('GET', '/backstage');

        // Check anonymous user is redirected (usually to /login)
        $this->assertResponseRedirects();
    }

    /**
     * Configure database and filesystem to simulate IF and ELSE cases.
     */
    private function setupFixturesForMediaPages(): void
    {
        // ==========================================
        // 1. ARCADE
        // ==========================================
        $arcadeRepo = $this->entityManager->getRepository(ArcadeType::class);

        // IF case: "bartop" type will have its directory and a file
        if (null === $arcadeRepo->findOneBy(['slug' => 'bartop'])) {
            $this->entityManager->persist((new ArcadeType())->setName('Bartop')->setSlug('bartop'));
        }
        $arcadeDir = $this->publicImagesDir . '/arcade/bartop';
        $this->filesystem->mkdir($arcadeDir);
        $this->filesystem->touch($arcadeDir . '/cabinet.jpg');

        // ELSE case: "dossier-fantome-arcade" type will not have a physical directory
        if (null === $arcadeRepo->findOneBy(['slug' => 'dossier-fantome-arcade'])) {
            $this->entityManager->persist((new ArcadeType())->setName('Fantome')->setSlug('dossier-fantome-arcade'));
        }

        // ==========================================
        // 2. CRÉATIONS
        // ==========================================
        $creationRepo = $this->entityManager->getRepository(CreationType::class);

        // IF case: "sculptures" type will have its directory and a file
        if (null === $creationRepo->findOneBy(['slug' => 'sculptures'])) {
            $this->entityManager->persist((new CreationType())->setName('Sculptures')->setSlug('sculptures'));
        }
        $creationDir = $this->publicImagesDir . '/creations/sculptures';
        $this->filesystem->mkdir($creationDir);
        $this->filesystem->touch($creationDir . '/pot-argile.jpg');

        // ELSE case: "dossier-fantome-creations" type will not have a physical directory
        if (null === $creationRepo->findOneBy(['slug' => 'dossier-fantome-creations'])) {
            $this->entityManager->persist((new CreationType())->setName('Fantome')->setSlug('dossier-fantome-creations'));
        }

        // ==========================================
        // 3. PHOTOS
        // ==========================================
        $photoRepo = $this->entityManager->getRepository(PhotoType::class);

        // IF case: "paysages" type will have its directory and files
        if (null === $photoRepo->findOneBy(['slug' => 'paysages'])) {
            $this->entityManager->persist((new PhotoType())->setName('Paysages')->setSlug('paysages'));
        }
        $photoDir = $this->publicImagesDir . '/photos/paysages';
        $this->filesystem->mkdir($photoDir);
        $this->filesystem->touch($photoDir . '/01-lever_de_soleil.jpg');
        $this->filesystem->touch($photoDir . '/sans_tiret.jpg');

        // ELSE case: "dossier-fantome-photos" type will not have a physical directory
        if (null === $photoRepo->findOneBy(['slug' => 'dossier-fantome-photos'])) {
            $this->entityManager->persist((new PhotoType())->setName('Fantome')->setSlug('dossier-fantome-photos'));
        }

        $this->entityManager->flush();
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
                ->setSubtitle('Du code au produit : mon parcours en mouvement')
                ->setSlug('experience')
                ->setTagline('Construire')
                ->setQuote('Le meilleur moyen de prédire l\'avenir, c\'est de le créer.')
                ->setPublished(true);
            $this->entityManager->persist($page);
            $this->entityManager->flush();
        }
    }
}
