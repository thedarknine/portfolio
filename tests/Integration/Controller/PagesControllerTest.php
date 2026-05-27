<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
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

class PagesControllerTest extends WebTestCase
{
    private $client;
    private EntityManagerInterface $entityManager;
    private Filesystem $filesystem;
    private string $publicImagesDir;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        $this->filesystem = new Filesystem();

        $projectDir = static::getContainer()->getParameter('kernel.project_dir');
        $this->publicImagesDir = $projectDir.'/public/images';

        $this->createRequiredPages();
        $this->setupFixturesForMediaPages();
    }

    protected function tearDown(): void
    {
        // Clean all temporary directories after each test
        $foldersToRemove = [
            $this->publicImagesDir.'/arcade/bartop',
            $this->publicImagesDir.'/creations/sculptures',
            $this->publicImagesDir.'/photos/paysages',
        ];

        foreach ($foldersToRemove as $folder) {
            if ($this->filesystem->exists($folder)) {
                $this->filesystem->remove($folder);
            }
        }

        parent::tearDown();
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
        $arcadeDir = $this->publicImagesDir.'/arcade/bartop';
        $this->filesystem->mkdir($arcadeDir);
        $this->filesystem->touch($arcadeDir.'/cabinet.jpg');

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
        $creationDir = $this->publicImagesDir.'/creations/sculptures';
        $this->filesystem->mkdir($creationDir);
        $this->filesystem->touch($creationDir.'/pot-argile.jpg');

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
        $photoDir = $this->publicImagesDir.'/photos/paysages';
        $this->filesystem->mkdir($photoDir);
        $this->filesystem->touch($photoDir.'/01-lever_de_soleil.jpg');
        $this->filesystem->touch($photoDir.'/sans_tiret.jpg');

        // ELSE case: "dossier-fantome-photos" type will not have a physical directory
        if (null === $photoRepo->findOneBy(['slug' => 'dossier-fantome-photos'])) {
            $this->entityManager->persist((new PhotoType())->setName('Fantome')->setSlug('dossier-fantome-photos'));
        }

        $this->entityManager->flush();
    }

    public function testIndexPageIsSuccessful(): void
    {
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    #[DataProvider('provideSlugs')]
    public function testAllDynamicPagesAreSuccessful(string $slug): void
    {
        $this->client->request('GET', '/'.$slug);
        $this->assertResponseIsSuccessful();
    }

    public static function provideSlugs(): array
    {
        return [
            ['experience'],
            ['competences'],
            ['formation'],
            ['projets'],
            ['arcade'],
            ['creations'],
            ['photos'],
        ];
    }

    public function testUnknownSlugTriggersNotFound(): void
    {
        $this->client->request('GET', '/un-slug-totalement-inconnu');
        $this->assertResponseStatusCodeSame(404);
    }

    #[DataProvider('provideSlugsAndTitles')]
    public function testDynamicPagesAreSuccessful(string $slug, string $expectedTagline): array
    {
        $this->client->request('GET', '/'.$slug);

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('.nine-section-label', $expectedTagline);

        return [$slug, $expectedTagline];
    }

    public static function provideSlugsAndTitles(): array
    {
        return [
            'page_experience' => ['experience', 'Construire'],
            'page_competences' => ['competences', 'Maîtriser'],
            'page_formation' => ['formation', 'Apprendre'],
            'page_projets' => ['projets', 'Expérimenter'],
            'page_arcade' => ['arcade', 'Assembler'],
            'page_creations' => ['creations', 'Façonner'],
            'page_photos' => ['photos', 'Observer'],
        ];
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

    /**
     * Test admin zone is protected and redirects anonymous users.
     */
    public function testAdminZoneIsProtected(): void
    {
        $this->client->request('GET', '/admin');

        // Check anonymous user is redirected (usually to /login)
        $this->assertResponseRedirects();
    }
}
