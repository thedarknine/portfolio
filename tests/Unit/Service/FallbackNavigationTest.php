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
use App\Service\FallbackNavigation;
use PHPUnit\Framework\TestCase;

class FallbackNavigationTest extends TestCase
{
    private FallbackNavigation $navigation;

    protected function setUp(): void
    {
        $this->navigation = new FallbackNavigation();
    }

    public function testRootPagesArePublishedAndNumberedFromOne(): void
    {
        $pages = $this->navigation->getRootPages();

        $this->assertCount(9, $pages);
        $this->assertSame(range(1, 9), array_map(fn (PageInfo $page): ?int => $page->getPosition(), $pages));
        foreach ($pages as $page) {
            $this->assertTrue($page->isPublished());
        }
        $this->assertSame('experience', $pages[0]->getSlug());
        $this->assertSame('mentions-legales', $pages[8]->getSlug());
    }

    public function testRootPagesFilterOnInHeader(): void
    {
        $this->assertSame(
            ['experience', 'competences', 'formation', 'projets', 'arcade', 'contact'],
            $this->slugs($this->navigation->getRootPages(inHeader: true)),
        );
        // The first page is in the header: filtering must skip it, not stop there
        $this->assertSame(
            ['creations', 'photos', 'mentions-legales'],
            $this->slugs($this->navigation->getRootPages(inHeader: false)),
        );
    }

    public function testPagesAsArrayArePublishedAndNumberedFromOne(): void
    {
        $pages = $this->navigation->getPagesAsArray();

        $this->assertCount(9, $pages);
        $this->assertSame(range(1, 9), array_column($pages, 'position'));
        $this->assertSame(array_fill(0, 9, true), array_column($pages, 'published'));
    }

    public function testPagesAsArrayFilterOnInHeader(): void
    {
        $this->assertSame(
            ['experience', 'competences', 'formation', 'projets', 'arcade', 'contact'],
            array_column($this->navigation->getPagesAsArray(inHeader: true), 'slug'),
        );
        $this->assertSame(
            ['creations', 'photos', 'mentions-legales'],
            array_column($this->navigation->getPagesAsArray(inHeader: false), 'slug'),
        );
    }

    public function testResourceLinksArePublishedAndNumberedFromZero(): void
    {
        $links = $this->navigation->getResourceLinksAsArray();

        $this->assertCount(6, $links);
        $this->assertSame(range(0, 5), array_column($links, 'position'));
        $this->assertSame(array_fill(0, 6, true), array_column($links, 'published'));
    }

    public function testResourceLinksFilterOnInHero(): void
    {
        $this->assertSame(
            ['linkedin', 'instagram', 'contact', 'cv-format-pdf'],
            array_column($this->navigation->getResourceLinksAsArray(inHero: true), 'slug'),
        );
        // The first link is in the hero: filtering must skip it, not stop there
        $this->assertSame(
            ['spotify', 'github'],
            array_column($this->navigation->getResourceLinksAsArray(inHero: false), 'slug'),
        );
    }

    /**
     * @param PageInfo[] $pages
     *
     * @return list<string|null>
     */
    private function slugs(array $pages): array
    {
        return array_map(fn (PageInfo $page): ?string => $page->getSlug(), $pages);
    }
}
