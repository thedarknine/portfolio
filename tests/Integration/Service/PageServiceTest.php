<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Service;

use App\Service\PageService;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PageServiceTest extends KernelTestCase
{
    private PageService $pageService;

    protected function setUp(): void
    {
        self::bootKernel();
        // Get the service directly from the private test container
        $this->pageService = self::getContainer()->get(PageService::class);
    }

    protected function tearDown(): void
    {
        // Always release the fake time with Carbon to avoid side effects on other tests
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function testGetActivePageBySlugReturnsPage(): void
    {
        // Assuming your fixtures created a page with the slug 'experience'
        $page = $this->pageService->getActivePageBySlug('experience');

        $this->assertNotNull($page);
        $this->assertSame('experience', $page->getSlug());
    }

    public function testGetActivePageBySlugThrowsNotFound(): void
    {
        // Verify that the expected exception is thrown if the page does not exist
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        $this->pageService->getActivePageBySlug('slug-qui-n-existe-pas');
    }

    public function testGetNbYearsExperience(): void
    {
        // 1. Freeze time to a specific date (e.g., May 28, 2026)
        // 2026 - 2006 (october) = 19 years of experience in May
        $fakeNow = Carbon::create(2026, 5, 28, 0, 0, 0);
        Carbon::setTestNow($fakeNow);

        $years = $this->pageService->getNbYearsExperience();

        $this->assertSame(19, $years);

        // 2. Test another date to kill the Infection mutants (e.g., just before the birthday in September 2026)
        $fakeAlmostAniversary = Carbon::create(2026, 9, 30, 0, 0, 0);
        Carbon::setTestNow($fakeAlmostAniversary);

        $this->assertSame(19, $this->pageService->getNbYearsExperience());

        // 3. Test exact day of the 20th birthday (October 1, 2026) to validate the limit
        $fakeAniversary = Carbon::create(2026, 10, 1, 0, 0, 0);
        Carbon::setTestNow($fakeAniversary);

        $this->assertSame(20, $this->pageService->getNbYearsExperience());
    }

    public function testGetImagesDir(): void
    {
        // Get actual project path configured in the test container
        $kernelProjectDir = self::getContainer()->getParameter('kernel.project_dir');
        $expectedPath = $kernelProjectDir.'/public/images';

        $actualPath = $this->pageService->getImagesDir();

        $this->assertSame($expectedPath, $actualPath);
        $this->assertStringEndsWith('/public/images', $actualPath);
    }
}
