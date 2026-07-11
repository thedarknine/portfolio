<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\EventSubscriber;

use App\Entity\PageInfo;
use App\Repository\ExperienceLinkRepository;
use App\Repository\PageInfoRepository;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private PageInfoRepository $pageInfoRepository,
        private ExperienceLinkRepository $experienceLinkRepository,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SitemapPopulateEvent::class => 'buildSitemap',
        ];
    }

    public function buildSitemap(SitemapPopulateEvent $event): void
    {
        foreach ($this->pageInfoRepository->findRootPages() as $rootPage) {
            foreach ($rootPage->getPublishedChildren() as $child) {
                $url = $this->buildChildUrl($event, $rootPage, $child);

                if (null !== $url) {
                    $event->getUrlContainer()->addUrl(
                        new UrlConcrete(
                            $url,
                            $child->getUpdatedAt(),
                            UrlConcrete::CHANGEFREQ_MONTHLY,
                            0.6,
                        ),
                        'default',
                    );
                }
            }
        }
    }

    private function buildChildUrl(SitemapPopulateEvent $event, PageInfo $rootPage, PageInfo $child): ?string
    {
        return match ($rootPage->getTechnicalName()) {
            'experience' => $this->buildExperienceChildUrl($event, $child),
            default      => null, // Unhandled section for now: silently ignore
        };
    }

    private function buildExperienceChildUrl(SitemapPopulateEvent $event, PageInfo $child): ?string
    {
        $experienceLink = $this->experienceLinkRepository->findOneByPage($child);

        if (null === $experienceLink || null === $experienceLink->getExperience()) {
            return null; // Orphan child page, no link to an experience -> ignore
        }

        return $event->getUrlGenerator()->generate(
            'app_experience_slug',
            [
                'job'  => $experienceLink->getExperience()->getSlug(),
                'slug' => $child->getSlug(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );
    }
}
