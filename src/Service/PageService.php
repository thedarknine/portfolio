<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Service;

use App\Entity\PageInfo;
use App\Repository\PageInfoRepository;
use App\Repository\ResourceLinkRepository;
use Carbon\Carbon;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageService
{
    public const CAREER_START_DATE = '2006-10-01';

    public function __construct(
        private PageInfoRepository $pageInfoRepository,
        private ResourceLinkRepository $resourceLinkRepository,
        private ParameterBagInterface $params,
    ) {
    }

    /**
     * Get active page by slug or throw 404 error.
     */
    public function getActivePageBySlug(string $slug): PageInfo
    {
        $page = $this->pageInfoRepository->findOneBy([
            'slug' => $slug,
            'published' => true,
        ]);

        if (!$page) {
            throw new NotFoundHttpException("La page demandée n'existe pas.");
        }

        return $page;
    }

    public function getPublishedPages(): array
    {
        return $this->pageInfoRepository->findAllAsArray(['published' => true]);
    }

    public function getPublishedPagesInHeader(): array
    {
        return $this->pageInfoRepository->findAllAsArray(['published' => true, 'inHeader' => true]);
    }

    public function getPublishedResourceLinks(): array
    {
        return $this->resourceLinkRepository->findAllAsArray(['published' => true]);
    }

    public function getPublishedResourceLinksInHero(): array
    {
        return $this->resourceLinkRepository->findAllAsArray(['published' => true, 'inHero' => true]);
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
}
