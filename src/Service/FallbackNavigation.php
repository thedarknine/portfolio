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
use App\Enum\PageCategory;

/**
 * Default navigation used when the database cannot be reached, so the header, the mobile menu and the footer still
 * render (on the 500 error page in particular). Keep it in line with the published pages and resource links.
 */
final class FallbackNavigation
{
    /**
     * Root pages, in menu order.
     *
     * @var list<array{slug: string, title: string, technicalName: string, category: PageCategory, inHeader: bool}>
     */
    private const array PAGES = [
        ['slug' => 'experience', 'title' => 'Expérience', 'technicalName' => 'experience', 'category' => PageCategory::CAREER, 'inHeader' => true],
        ['slug' => 'competences', 'title' => 'Compétences', 'technicalName' => 'skills', 'category' => PageCategory::CAREER, 'inHeader' => true],
        ['slug' => 'formation', 'title' => 'Formation', 'technicalName' => 'education', 'category' => PageCategory::CAREER, 'inHeader' => true],
        ['slug' => 'projets', 'title' => 'Projets', 'technicalName' => 'projects', 'category' => PageCategory::CAREER, 'inHeader' => true],
        ['slug' => 'arcade', 'title' => 'Arcade', 'technicalName' => 'arcade', 'category' => PageCategory::INTEREST, 'inHeader' => true],
        ['slug' => 'creations', 'title' => 'Créations', 'technicalName' => 'creations', 'category' => PageCategory::INTEREST, 'inHeader' => false],
        ['slug' => 'photos', 'title' => 'Photos', 'technicalName' => 'photos', 'category' => PageCategory::INTEREST, 'inHeader' => false],
        ['slug' => 'contact', 'title' => 'Contact', 'technicalName' => 'contact', 'category' => PageCategory::INTEREST, 'inHeader' => true],
        ['slug' => 'mentions-legales', 'title' => 'Mentions légales', 'technicalName' => 'legal', 'category' => PageCategory::ABOUT, 'inHeader' => false],
    ];

    /**
     * Resource links, in display order.
     *
     * @var list<array{slug: string, title: string, url: string, icon: string, inHero: bool}>
     */
    private const array RESOURCE_LINKS = [
        ['slug' => 'linkedin', 'title' => 'LinkedIn', 'url' => 'https://www.linkedin.com/in/carolinenoyer', 'icon' => 'fa7-brands:linkedin-in', 'inHero' => true],
        ['slug' => 'instagram', 'title' => 'Instagram', 'url' => 'https://instagram.com/caro.noyer', 'icon' => 'fa7-brands:instagram', 'inHero' => true],
        ['slug' => 'contact', 'title' => 'Contact', 'url' => 'https://carolinenoyer.fr/contact', 'icon' => 'fa7-solid:paper-plane', 'inHero' => true],
        ['slug' => 'cv-format-pdf', 'title' => 'CV format PDF', 'url' => 'https://docs.carolinenoyer.fr/cnoyer-cv-productowner.pdf', 'icon' => 'fa7-solid:file-pdf', 'inHero' => true],
        ['slug' => 'spotify', 'title' => 'Spotify', 'url' => 'https://open.spotify.com/playlist/3g3bePO9Jddljzgx5wS4IM', 'icon' => 'fa7-brands:spotify', 'inHero' => false],
        ['slug' => 'github', 'title' => 'GitHub', 'url' => 'https://github.com/thedarknine', 'icon' => 'fa7-brands:github-alt', 'inHero' => false],
    ];

    /**
     * Same shape as PageInfoRepository::findRootPages().
     *
     * @return PageInfo[]
     */
    public function getRootPages(?bool $inHeader = null): array
    {
        $pages = [];
        foreach (self::PAGES as $position => $data) {
            if (null !== $inHeader && $data['inHeader'] !== $inHeader) {
                continue;
            }

            $pages[] = new PageInfo()
                ->setTitle($data['title'])
                ->setSlug($data['slug'])
                ->setTechnicalName($data['technicalName'])
                ->setCategory($data['category'])
                ->setInHeader($data['inHeader'])
                ->setPosition($position + 1)
                ->setPublished(true);
        }

        return $pages;
    }

    /**
     * Same shape as PageInfoRepository::findAllAsArray().
     *
     * @return array<int, array<string, mixed>>
     */
    public function getPagesAsArray(?bool $inHeader = null): array
    {
        $pages = [];
        foreach (self::PAGES as $position => $data) {
            if (null !== $inHeader && $data['inHeader'] !== $inHeader) {
                continue;
            }

            $pages[] = $data + ['position' => $position + 1, 'published' => true];
        }

        return $pages;
    }

    /**
     * Same shape as ResourceLinkRepository::findAllAsArray().
     *
     * @return array<int, array<string, mixed>>
     */
    public function getResourceLinksAsArray(?bool $inHero = null): array
    {
        $links = [];
        foreach (self::RESOURCE_LINKS as $position => $data) {
            if (null !== $inHero && $data['inHero'] !== $inHero) {
                continue;
            }

            $links[] = $data + ['position' => $position, 'published' => true];
        }

        return $links;
    }
}
