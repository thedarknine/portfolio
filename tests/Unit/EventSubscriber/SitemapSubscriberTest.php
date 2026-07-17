<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\EventSubscriber;

use App\Entity\Experience;
use App\Entity\ExperienceLink;
use App\Entity\PageInfo;
use App\EventSubscriber\SitemapSubscriber;
use App\Repository\ExperienceLinkRepository;
use App\Repository\PageInfoRepository;
use PHPUnit\Framework\TestCase;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapSubscriberTest extends TestCase
{
    public function testGetSubscribedEvents(): void
    {
        $events = SitemapSubscriber::getSubscribedEvents();

        $this->assertArrayHasKey(SitemapPopulateEvent::class, $events);
        $this->assertSame('buildSitemap', $events[SitemapPopulateEvent::class]);
    }

    public function testBuildSitemapDoesNothingWhenNoRootPages(): void
    {
        $pageInfoRepository = $this->createMock(PageInfoRepository::class);
        $pageInfoRepository->expects($this->once())
            ->method('findRootPages')
            ->willReturn([]);

        $experienceLinkRepository = $this->createMock(ExperienceLinkRepository::class);
        $experienceLinkRepository->expects($this->never())
            ->method('findOneByPage');

        $urlContainer = $this->createMock(UrlContainerInterface::class);
        $urlContainer->expects($this->never())
            ->method('addUrl');

        $event = $this->createStub(SitemapPopulateEvent::class);
        $event->method('getUrlContainer')->willReturn($urlContainer);

        $subscriber = new SitemapSubscriber($pageInfoRepository, $experienceLinkRepository);
        $subscriber->buildSitemap($event);
    }

    public function testBuildSitemapSkipsRootPagesWithoutPublishedChildren(): void
    {
        $rootPage = $this->createStub(PageInfo::class);
        $rootPage->method('getTechnicalName')->willReturn('experience');
        $rootPage->method('getPublishedChildren')->willReturn([]);

        $pageInfoRepository = $this->createMock(PageInfoRepository::class);
        $pageInfoRepository->expects($this->once())
            ->method('findRootPages')
            ->willReturn([$rootPage]);

        $experienceLinkRepository = $this->createMock(ExperienceLinkRepository::class);
        $experienceLinkRepository->expects($this->never())
            ->method('findOneByPage');

        $urlContainer = $this->createMock(UrlContainerInterface::class);
        $urlContainer->expects($this->never())
            ->method('addUrl');

        $event = $this->createStub(SitemapPopulateEvent::class);
        $event->method('getUrlContainer')->willReturn($urlContainer);

        $subscriber = new SitemapSubscriber($pageInfoRepository, $experienceLinkRepository);
        $subscriber->buildSitemap($event);
    }

    public function testBuildSitemapSkipsUnhandledSections(): void
    {
        $child = $this->createStub(PageInfo::class);

        $rootPage = $this->createStub(PageInfo::class);
        $rootPage->method('getTechnicalName')->willReturn('projets');
        $rootPage->method('getPublishedChildren')->willReturn([$child]);

        $pageInfoRepository = $this->createMock(PageInfoRepository::class);
        $pageInfoRepository->expects($this->once())
            ->method('findRootPages')
            ->willReturn([$rootPage]);

        $experienceLinkRepository = $this->createMock(ExperienceLinkRepository::class);
        $experienceLinkRepository->expects($this->never())
            ->method('findOneByPage');

        $urlContainer = $this->createMock(UrlContainerInterface::class);
        $urlContainer->expects($this->never())
            ->method('addUrl');

        $event = $this->createStub(SitemapPopulateEvent::class);
        $event->method('getUrlContainer')->willReturn($urlContainer);

        $subscriber = new SitemapSubscriber($pageInfoRepository, $experienceLinkRepository);
        $subscriber->buildSitemap($event);
    }

    public function testBuildSitemapSkipsOrphanChildPages(): void
    {
        $child = $this->createStub(PageInfo::class);
        $child->method('getSlug')->willReturn('page-1');

        $rootPage = $this->createStub(PageInfo::class);
        $rootPage->method('getTechnicalName')->willReturn('experience');
        $rootPage->method('getPublishedChildren')->willReturn([$child]);

        $pageInfoRepository = $this->createMock(PageInfoRepository::class);
        $pageInfoRepository->expects($this->once())
            ->method('findRootPages')
            ->willReturn([$rootPage]);

        $experienceLinkRepository = $this->createMock(ExperienceLinkRepository::class);
        $experienceLinkRepository->expects($this->once())
            ->method('findOneByPage')
            ->with($child)
            ->willReturn(null); // aucune ExperienceLink rattachée à cette page

        $urlContainer = $this->createMock(UrlContainerInterface::class);
        $urlContainer->expects($this->never())
            ->method('addUrl');

        $event = $this->createStub(SitemapPopulateEvent::class);
        $event->method('getUrlContainer')->willReturn($urlContainer);

        $subscriber = new SitemapSubscriber($pageInfoRepository, $experienceLinkRepository);
        $subscriber->buildSitemap($event);
    }

    public function testBuildSitemapAddsUrlsForExperienceChildren(): void
    {
        $updatedAt = new \DateTime('2026-01-15');

        $child1 = $this->createStub(PageInfo::class);
        $child1->method('getSlug')->willReturn('page-1');
        $child1->method('getUpdatedAt')->willReturn($updatedAt);

        $child2 = $this->createStub(PageInfo::class);
        $child2->method('getSlug')->willReturn('page-2');
        $child2->method('getUpdatedAt')->willReturn($updatedAt);

        $rootPage = $this->createStub(PageInfo::class);
        $rootPage->method('getTechnicalName')->willReturn('experience');
        $rootPage->method('getPublishedChildren')->willReturn([$child1, $child2]);

        $pageInfoRepository = $this->createMock(PageInfoRepository::class);
        $pageInfoRepository->expects($this->once())
            ->method('findRootPages')
            ->willReturn([$rootPage]);

        $experience1 = $this->createStub(Experience::class);
        $experience1->method('getSlug')->willReturn('perfect-memory');

        $experience2 = $this->createStub(Experience::class);
        $experience2->method('getSlug')->willReturn('another-company');

        $link1 = $this->createStub(ExperienceLink::class);
        $link1->method('getExperience')->willReturn($experience1);

        $link2 = $this->createStub(ExperienceLink::class);
        $link2->method('getExperience')->willReturn($experience2);

        $experienceLinkRepository = $this->createMock(ExperienceLinkRepository::class);
        $experienceLinkRepository->expects($this->exactly(2))
            ->method('findOneByPage')
            ->willReturnMap([
                [$child1, $link1],
                [$child2, $link2],
            ]);

        $urlGenerator  = $this->createMock(UrlGeneratorInterface::class);
        $expectedCalls = [
            ['app_experience_slug', ['job' => 'perfect-memory', 'slug' => 'page-1'], UrlGeneratorInterface::ABSOLUTE_URL, 'https://example.test/experience/perfect-memory/page-1'],
            ['app_experience_slug', ['job' => 'another-company', 'slug' => 'page-2'], UrlGeneratorInterface::ABSOLUTE_URL, 'https://example.test/experience/another-company/page-2'],
        ];
        $urlGenerator->expects($this->exactly(2))
            ->method('generate')
            ->willReturnCallback(function (string $route, array $parameters, int $referenceType) use (&$expectedCalls) {
                $expected = array_shift($expectedCalls);

                $this->assertSame($expected[0], $route);
                $this->assertSame($expected[1], $parameters);
                $this->assertSame($expected[2], $referenceType);

                return $expected[3];
            });

        $calls        = [];
        $urlContainer = $this->createStub(UrlContainerInterface::class);
        $urlContainer->method('addUrl')
            ->willReturnCallback(function ($url, $section) use (&$calls) {
                $calls[] = [$url, $section];
            });

        $event = $this->createStub(SitemapPopulateEvent::class);
        $event->method('getUrlGenerator')->willReturn($urlGenerator);
        $event->method('getUrlContainer')->willReturn($urlContainer);

        $subscriber = new SitemapSubscriber($pageInfoRepository, $experienceLinkRepository);
        $subscriber->buildSitemap($event);

        $this->assertCount(2, $calls);
        $this->assertInstanceOf(UrlConcrete::class, $calls[0][0]);
        $this->assertSame('default', $calls[0][1]);
        $this->assertInstanceOf(UrlConcrete::class, $calls[1][0]);
        $this->assertSame('default', $calls[1][1]);
    }
}
