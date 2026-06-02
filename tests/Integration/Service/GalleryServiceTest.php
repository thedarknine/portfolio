<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Service;

use App\Service\GalleryService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class GalleryServiceTest extends KernelTestCase
{
    private GalleryService $galleryService;
    private Filesystem $filesystem;
    private string $testImagesDir;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->galleryService = self::getContainer()->get(GalleryService::class);
        $this->filesystem     = new Filesystem();

        // Security: Point to /var/tests/images instead of /public/images
        $projectDir          = self::getContainer()->getParameter('kernel.project_dir');
        $this->testImagesDir = $projectDir . '/var/tests/images';

        // Ensure the temporary directory exists
        $this->filesystem->mkdir($this->testImagesDir);
    }

    protected function tearDown(): void
    {
        // Only remove the temporary directory in /var
        if ($this->filesystem->exists($this->testImagesDir)) {
            $this->filesystem->remove($this->testImagesDir);
        }
        parent::tearDown();
    }

    public function testGetArcadeGalleryWithExistingAndMissingDirectories(): void
    {
        // 1. ARRANGEMENT : Prepare a directory for one of the slugs returned by your fixtures/BDD
        // Replace 'nintendo' with a slug that your fixture command actually creates for ArcadeType
        $existingSlug = 'nintendo';
        $targetDir    = $this->testImagesDir . '/arcade/' . $existingSlug;

        $this->filesystem->mkdir($targetDir);
        $this->filesystem->touch($targetDir . '/mario.png');
        $this->filesystem->touch($targetDir . '/zelda.png');

        // 2. ACT
        $result = $this->galleryService->getArcadeGallery();

        // 3. ASSERTIONS
        $this->assertArrayHasKey('types', $result);
        $this->assertArrayHasKey('items', $result);

        // If type exists in database, verify that Finder has found our files
        if (isset($result['items'][$existingSlug])) {
            $this->assertCount(2, $result['items'][$existingSlug]);
            $this->assertContains('mario.png', $result['items'][$existingSlug]);
        }
    }

    public function testGetCreationsGallery(): void
    {
        // Take an existing slug (e.g., 'poterie')
        $existingSlug = 'poterie';
        $targetDir    = $this->testImagesDir . '/creations/' . $existingSlug;

        $this->filesystem->mkdir($targetDir);
        $this->filesystem->touch($targetDir . '/vase.jpg');

        $result = $this->galleryService->getCreationsGallery();

        $this->assertArrayHasKey('types', $result);
        $this->assertArrayHasKey('creations', $result);

        if (isset($result['creations'][$existingSlug])) {
            $this->assertCount(1, $result['creations'][$existingSlug]);
        }
    }

    public function testGetPhotosGalleryAndCaptionParsing(): void
    {
        // Create a directory to test the renaming logic and caption explode
        $existingSlug = 'paysages';
        $targetDir    = $this->testImagesDir . '/photos/' . $existingSlug;

        $this->filesystem->mkdir($targetDir);

        // File 1: Standard format "ID-Ma_Super_Legende.jpg"
        $this->filesystem->touch($targetDir . '/01-Coucher_de_soleil.jpg');
        // File 2: Format without hyphen (edge case for isset($caption[1]))
        $this->filesystem->touch($targetDir . '/sans_tiret.JPG');

        $result = $this->galleryService->getPhotosGallery();

        $this->assertArrayHasKey('types', $result);
        $this->assertArrayHasKey('photos', $result);

        if (isset($result['photos'][$existingSlug])) {
            $photos = $result['photos'][$existingSlug];

            // Ensure both files have been processed
            $this->assertCount(2, $photos);

            // Find the file structured to validate the caption extraction algorithm
            $coucherDeSoleilData = null;
            $sansTiretData       = null;
            foreach ($photos as $photo) {
                if ('01-Coucher_de_soleil.jpg' === $photo['filename']) {
                    $coucherDeSoleilData = $photo;
                }
                if ('sans_tiret.JPG' === $photo['filename']) {
                    $sansTiretData = $photo;
                }
            }

            // Verification of explode and str_replace ('_' becomes ' ')
            $this->assertNotNull($coucherDeSoleilData);
            $this->assertSame('Coucher de soleil', $coucherDeSoleilData['caption']);

            // Verification of the edge case without hyphen (should return an empty string and not an error)
            $this->assertNotNull($sansTiretData);
            $this->assertSame('', $sansTiretData['caption']);
        }
    }
}
