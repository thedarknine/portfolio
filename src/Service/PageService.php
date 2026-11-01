<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Service;

use App\Entity\PageInfo;
use App\Repository\PageInfoRepository;
use App\Repository\ResourceLinkRepository;
use Carbon\Carbon;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageService
{
    public const CAREER_START_DATE = '2006-10-01';

    /** Set to false after the first failed query, so the rest of the request uses the fallback without retrying. */
    private bool $databaseAvailable = true;

    public function __construct(
        private PageInfoRepository $pageInfoRepository,
        private ResourceLinkRepository $resourceLinkRepository,
        private ParameterBagInterface $params,
        private FallbackNavigation $fallbackNavigation,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * Get active page by slug or throw 404 error.
     */
    public function getActivePageBySlug(string $slug): PageInfo
    {
        $page = $this->pageInfoRepository->findOneBy([
            'slug'      => $slug,
            'published' => true,
        ]);

        if (!$page) {
            throw new NotFoundHttpException("La page demandée n'existe pas.");
        }

        if ($page->getParent()?->getId()) {
            $parentPage = $this->pageInfoRepository->findOneBy([
                'id' => $page->getParent()->getId(),
            ]);
            $page->setParent($parentPage);
        }

        return $page;
    }

    /**
     * @return PageInfo[]
     */
    public function getPublishedRootPages(): array
    {
        return $this->queryOrFallback(
            fn (): array => $this->pageInfoRepository->findRootPages(),
            fn (): array => $this->fallbackNavigation->getRootPages(),
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getPublishedPages(?bool $inHeader = null): array
    {
        $filters = ['published' => true];
        if (null !== $inHeader) {
            $filters['inHeader'] = $inHeader;
        }

        return $this->queryOrFallback(
            fn (): array => $this->pageInfoRepository->findAllAsArray($filters),
            fn (): array => $this->fallbackNavigation->getPagesAsArray($inHeader),
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getPublishedPagesInHeader(): array
    {
        return $this->getPublishedPages(true);
    }

    /**
     * @return PageInfo[]
     */
    public function getPublishedRootPagesInHeader(): array
    {
        return $this->queryOrFallback(
            fn (): array => $this->pageInfoRepository->findPublishedRootPagesInHeader(),
            fn (): array => $this->fallbackNavigation->getRootPages(inHeader: true),
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getPublishedResourceLinks(?bool $inHero = null): array
    {
        $filters = ['published' => true];
        if (null !== $inHero) {
            $filters['inHero'] = $inHero;
        }

        return $this->queryOrFallback(
            fn (): array => $this->resourceLinkRepository->findAllAsArray($filters),
            fn (): array => $this->fallbackNavigation->getResourceLinksAsArray($inHero),
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getPublishedResourceLinksInHero(): array
    {
        return $this->getPublishedResourceLinks(true);
    }

    public function getNbYearsExperience(): int
    {
        $startWorking = new Carbon(self::CAREER_START_DATE);

        return (int) $startWorking->diff(Carbon::now())->y;
    }

    public function getStartCareerYear(): int
    {
        $startWorking = new Carbon(self::CAREER_START_DATE);

        return (int) $startWorking->year;
    }

    public function getImagesDir(): string
    {
        return $this->params->get('app.images_dir');
    }

    /**
     * Run a navigation query, or return the fallback navigation when the query fails (database down, in practice).
     * Any exception is caught, not only Doctrine's: the Service layer must not depend on Doctrine (deptrac), and a
     * degraded menu is better than a page that cannot render. The failure is logged as an error.
     *
     * @template T
     *
     * @param callable(): T $query
     * @param callable(): T $fallback
     *
     * @return T
     */
    private function queryOrFallback(callable $query, callable $fallback): mixed
    {
        if (!$this->databaseAvailable) {
            return $fallback();
        }

        try {
            return $query();
        } catch (\Exception $exception) {
            $this->databaseAvailable = false;
            $this->logger->error('Navigation query failed: the fallback pages are used.', ['exception' => $exception]);

            return $fallback();
        }
    }
}
