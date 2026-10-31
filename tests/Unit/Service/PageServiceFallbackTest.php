<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Service;

use App\Entity\PageInfo;
use App\Repository\PageInfoRepository;
use App\Repository\ResourceLinkRepository;
use App\Service\FallbackNavigation;
use App\Service\PageService;
use Doctrine\DBAL\Exception as DatabaseException;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AllowMockObjectsWithoutExpectations]
class PageServiceFallbackTest extends TestCase
{
    private PageInfoRepository&MockObject $pageInfoRepository;
    private ResourceLinkRepository&MockObject $resourceLinkRepository;
    private LoggerInterface&MockObject $logger;
    private PageService $pageService;

    protected function setUp(): void
    {
        $this->pageInfoRepository     = $this->createMock(PageInfoRepository::class);
        $this->resourceLinkRepository = $this->createMock(ResourceLinkRepository::class);
        $this->logger                 = $this->createMock(LoggerInterface::class);

        $this->pageService = new PageService(
            $this->pageInfoRepository,
            $this->resourceLinkRepository,
            $this->createStub(ParameterBagInterface::class),
            new FallbackNavigation(),
            $this->logger,
        );
    }

    public function testRepositoryResultIsReturnedWhenTheDatabaseAnswers(): void
    {
        $page = new PageInfo()->setTitle('Expérience')->setSlug('experience');
        $this->pageInfoRepository->method('findPublishedRootPagesInHeader')->willReturn([$page]);
        $this->logger->expects($this->never())->method('error');

        $this->assertSame([$page], $this->pageService->getPublishedRootPagesInHeader());
    }

    public function testHeaderFallsBackToDefaultPagesWhenTheDatabaseIsDown(): void
    {
        $this->pageInfoRepository->method('findPublishedRootPagesInHeader')->willThrowException($this->databaseDown());

        $pages = $this->pageService->getPublishedRootPagesInHeader();

        $this->assertNotEmpty($pages);
        $this->assertContainsOnlyInstancesOf(PageInfo::class, $pages);
        foreach ($pages as $page) {
            $this->assertTrue($page->isInHeader());
        }
        $this->assertContains('contact', array_map(fn (PageInfo $page): ?string => $page->getSlug(), $pages));
    }

    public function testFooterFallsBackToPagesAndResourceLinksAsArrays(): void
    {
        $this->pageInfoRepository->method('findAllAsArray')->willThrowException($this->databaseDown());

        $pages = $this->pageService->getPublishedPages();
        $links = $this->pageService->getPublishedResourceLinks();

        $this->assertNotEmpty($pages);
        $this->assertArrayHasKey('category', $pages[0]);
        $this->assertNotEmpty($links);
        foreach ($links as $link) {
            $this->assertArrayHasKey('url', $link);
            $this->assertArrayHasKey('icon', $link);
        }
    }

    public function testDatabaseIsQueriedOnlyOnceAfterAFailure(): void
    {
        $exception = $this->databaseDown();
        $this->pageInfoRepository->expects($this->once())
            ->method('findRootPages')
            ->willThrowException($exception);
        $this->pageInfoRepository->expects($this->never())->method('findPublishedRootPagesInHeader');
        $this->resourceLinkRepository->expects($this->never())->method('findAllAsArray');
        // The exception goes to the log context, so the logs keep its message and trace
        $this->logger->expects($this->once())
            ->method('error')
            ->with($this->isString(), ['exception' => $exception]);

        $this->pageService->getPublishedRootPages();
        $this->pageService->getPublishedRootPagesInHeader();
        $this->pageService->getPublishedResourceLinks();
    }

    public function testInHeaderAndInHeroFiltersApplyToTheFallback(): void
    {
        $this->pageInfoRepository->method('findAllAsArray')->willThrowException($this->databaseDown());

        foreach ($this->pageService->getPublishedPagesInHeader() as $page) {
            $this->assertTrue($page['inHeader']);
        }
        foreach ($this->pageService->getPublishedResourceLinksInHero() as $link) {
            $this->assertTrue($link['inHero']);
        }
    }

    private function databaseDown(): DatabaseException
    {
        return new class('Connection refused') extends \RuntimeException implements DatabaseException {
        };
    }
}
