<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Service;

use App\Kernel;
use App\Repository\ExperienceRepository;
use App\Repository\PageInfoRepository;
use App\Repository\ProjectRepository;
use App\Repository\SkillRepository;
use App\Service\AdminDashboardService;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AllowMockObjectsWithoutExpectations]
class AdminDashboardServiceTest extends TestCase
{
    private ParameterBagInterface&MockObject $params;
    private Kernel&Stub $kernel;
    private ProjectRepository&MockObject $projectRepository;
    private PageInfoRepository&MockObject $pageInfoRepository;
    private SkillRepository&MockObject $skillRepository;
    private ExperienceRepository&MockObject $experienceRepository;

    protected function setUp(): void
    {
        $this->params = $this->createMock(ParameterBagInterface::class);
        $this->kernel = $this->createStub(Kernel::class);
        $this->projectRepository = $this->createMock(ProjectRepository::class);
        $this->pageInfoRepository = $this->createMock(PageInfoRepository::class);
        $this->skillRepository = $this->createMock(SkillRepository::class);
        $this->experienceRepository = $this->createMock(ExperienceRepository::class);
    }

    private function createService(): AdminDashboardService
    {
        return new AdminDashboardService(
            $this->params,
            $this->kernel,
            $this->projectRepository,
            $this->pageInfoRepository,
            $this->skillRepository,
            $this->experienceRepository
        );
    }

    /**
     * Nominal test on main statistics.
     */
    public function testGetStatsReturnsCompleteArray(): void
    {
        $this->projectRepository->method('count')->willReturn(15);
        $this->experienceRepository->method('count')->willReturn(3);

        $this->pageInfoRepository->expects($this->once())->method('count')->willReturn(5);
        $this->skillRepository->expects($this->once())->method('count')->willReturn(42);

        $fakeProject = (object) [
            'title' => 'Mon super projet',
            'year' => 2026,
        ];

        $this->projectRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($fakeProject);

        // Expect that the parameter is queried during the test
        $this->params->expects($this->atLeastOnce())
            ->method('get')
            ->with('kernel.project_dir')
            ->willReturn('/un/dossier/fantome/qui/n/existe/pas');

        $service = $this->createService();
        $stats = $service->getStats();

        $this->assertIsArray($stats);
        $this->assertEquals(15, $stats['projects']);
        $this->assertEquals(5, $stats['publicPages']);
        $this->assertEquals(42, $stats['technologiesCount']);
        $this->assertEquals('Mon super projet', $stats['latestProject']->title);
        $this->assertNull($stats['latestPhoto']);
        $this->assertEquals(0, $stats['totalImages']);
        $this->assertEquals('0 B', $stats['portfolioDiskUsage']);
        $this->assertEquals('5.0', $stats['projectsExperienceRatio']);
        $this->assertNotEmpty($stats['phpVersion']);
        $this->assertMatchesRegularExpression('/^\d+\.\d+\.\d+|Unavailable$/', $stats['phpVersion']);
        $this->assertIsInt($stats['yearsExperience']);
        $this->assertGreaterThanOrEqual(19, $stats['yearsExperience']);
    }

    /**
     * Security test to avoid division by zero.
     */
    public function testProjectsExperienceRatioWhenZeroExperiences(): void
    {
        $this->projectRepository->method('count')->willReturn(10);
        $this->experienceRepository->method('count')->willReturn(0);

        // Use method()->willReturn() directly without expects()
        $this->params->method('get')->willReturn('/dossier/vide');

        $service = $this->createService();
        $stats = $service->getStats();

        $this->assertEquals('0', $stats['projectsExperienceRatio']);
    }

    /**
     * Test file size formatting.
     */
    public function testDirectorySizeWithRealTemporaryFiles(): void
    {
        $tmpDir = sys_get_temp_dir().'/dashboard_service_test_'.uniqid();
        mkdir($tmpDir.'/public/images/photos', 0777, true);

        // Verify that the get method is called at least once
        $this->params->expects($this->atLeastOnce())
            ->method('get')
            ->with('kernel.project_dir')
            ->willReturn($tmpDir);

        $fileContent = str_repeat('A', 1536);
        file_put_contents($tmpDir.'/public/images/photo1.jpg', $fileContent);
        file_put_contents($tmpDir.'/public/images/photos/photo2.jpg', 'fake-image-data');

        $service = $this->createService();
        $stats = $service->getStats();

        $this->assertStringContainsString('KB', $stats['portfolioDiskUsage']);
        $this->assertEquals(2, $stats['totalImages']);
        $this->assertNotNull($stats['latestPhoto']);
        $this->assertEquals('photo2.jpg', $stats['latestPhoto']['filename']);

        unlink($tmpDir.'/public/images/photos/photo2.jpg');
        unlink($tmpDir.'/public/images/photo1.jpg');
        rmdir($tmpDir.'/public/images/photos');
        rmdir($tmpDir.'/public/images');
        rmdir($tmpDir.'/public');
        rmdir($tmpDir);
    }

    public function testGetLatestPhotoWhenDirectoryIsEmpty(): void
    {
        // Create isolated temporary directory
        $tmpDir = sys_get_temp_dir().'/dashboard_service_empty_test_'.uniqid();
        mkdir($tmpDir.'/public/images/photos', 0777, true);

        // Place only the file excluded by your filter
        file_put_contents($tmpDir.'/public/images/photos/hero-photos.jpg', 'hero');

        // Configuration of the stub for ALL parameter requests in this test
        $this->params->expects($this->atLeastOnce())
            ->method('get')
            ->with('kernel.project_dir')
            ->willReturn($tmpDir);

        $service = $this->createService();
        $stats = $service->getStats();

        // Verification: The filter has emptied everything, we should be null (Line 80 covered!)
        $this->assertNull($stats['latestPhoto']);

        // Clean up
        unlink($tmpDir.'/public/images/photos/hero-photos.jpg');
        rmdir($tmpDir.'/public/images/photos');
        rmdir($tmpDir.'/public/images');
        rmdir($tmpDir.'/public');
        rmdir($tmpDir);
    }
}
