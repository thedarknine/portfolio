<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Repository;

use App\Entity\PageInfo;
use App\Enum\PageCategory;
use App\Repository\PageInfoRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PageInfoRepositoryTest extends KernelTestCase
{
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager;
    private PageInfoRepository $pageInfoRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager      = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->pageInfoRepository = $this->entityManager->getRepository(PageInfo::class);

        $this->cleanUpDatabase();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->cleanUpDatabase();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    /**
     * Test that findAllAsArray returns sorted arrays by position.
     */
    public function testFindAllAsArrayReturnsSortedArrays(): void
    {
        // 1. Insert 3 pages in random order by position
        $pageArcade = $this->createPage('Arcade', 'arcade', PageCategory::INTEREST);
        $pageArcade->setPosition(2);
        $pagePhotos = $this->createPage('Photos', 'photos', PageCategory::INTEREST);
        $pagePhotos->setPosition(3);
        $pageProjets = $this->createPage('Projets', 'projets', PageCategory::CAREER);
        $pageProjets->setPosition(1);

        $this->entityManager->persist($pageArcade);
        $this->entityManager->persist($pagePhotos);
        $this->entityManager->persist($pageProjets);
        $this->entityManager->flush();

        // 2. Call the method under test
        $results = $this->pageInfoRepository->findAllAsArray();

        // 3. Assertions
        $this->assertCount(3, $results);

        // Verify that the results are arrays and not objects
        $this->assertIsArray($results[0], 'The result of getArrayResult should be a raw array');

        // Validate the ASC order based on position (Projets [pos 1] -> Arcade [pos 2] -> Photos [pos 3])
        $this->assertSame('projets', $results[0]['slug'], 'The page "Projets" (position 1) should be first.');
        $this->assertSame('arcade', $results[1]['slug'], 'The page "Arcade" (position 2) should be second.');
        $this->assertSame('photos', $results[2]['slug'], 'The page "Photos" (position 3) should be third.');

        // Optional: Ensure the expected entity keys are present
        $this->assertArrayHasKey('title', $results[0]);
        $this->assertArrayHasKey('tagline', $results[0]);
    }

    /**
     * Test that findByParentId returns only the children of the given parent.
     */
    public function testFindByParentIdReturnsChildrenOfGivenParent(): void
    {
        $parent1 = $this->createPage('Parent 1', 'parent-1');
        $parent2 = $this->createPage('Parent 2', 'parent-2');

        $child1     = $this->createPage('Child 1', 'child-1', PageCategory::CAREER, $parent1);
        $child2     = $this->createPage('Child 2', 'child-2', PageCategory::CAREER, $parent1);
        $otherChild = $this->createPage('Other Child', 'other-child', PageCategory::CAREER, $parent2);

        foreach ([$parent1, $parent2, $child1, $child2, $otherChild] as $page) {
            $this->entityManager->persist($page);
        }

        $this->entityManager->flush();

        $results = $this->pageInfoRepository->findByParentId($parent1->getId());

        $this->assertCount(2, $results);
        $this->assertContains($child1, $results);
        $this->assertContains($child2, $results);
        $this->assertNotContains($otherChild, $results);

        foreach ($results as $page) {
            $this->assertSame($parent1->getId(), $page->getParent()?->getId());
        }
    }

    /**
     * Test that findByParentId returns an empty array when no child exists.
     */
    public function testFindByParentIdReturnsEmptyArrayWhenNoChildrenExist(): void
    {
        $parent = $this->createPage('Parent', 'parent');

        $this->entityManager->persist($parent);
        $this->entityManager->flush();

        $results = $this->pageInfoRepository->findByParentId($parent->getId());

        $this->assertSame([], $results);
    }

    public function testFindChildrenPagesReturnsPublishedChildrenOfGivenParent(): void
    {
        $parent           = $this->createPage('parent-page', 'parent-page', PageCategory::CAREER, null);
        $publishedChild   = $this->createPage('published-child', 'published-child', PageCategory::CAREER, $parent);
        $unpublishedChild = $this->createPage('unpublished-child', 'unpublished-child', PageCategory::CAREER, $parent, false);

        $this->entityManager->persist($parent);
        $this->entityManager->persist($publishedChild);
        $this->entityManager->persist($unpublishedChild);
        $this->entityManager->flush();

        $result = $this->pageInfoRepository->findChildrenPages(published: true, parentId: $parent->getId());

        $slugs = array_map(fn (PageInfo $page) => $page->getSlug(), $result);

        $this->assertContains($publishedChild->getSlug(), $slugs);
        $this->assertNotContains($unpublishedChild->getSlug(), $slugs);
    }

    public function testFindChildrenPagesIncludesUnpublishedWhenPublishedFalse(): void
    {
        $parent           = $this->createPage('parent-page-2', 'parent-page-2', PageCategory::CAREER, null);
        $unpublishedChild = $this->createPage('unpublished-child-2', 'unpublished-child-2', PageCategory::CAREER, $parent, false);

        $this->entityManager->persist($parent);
        $this->entityManager->persist($unpublishedChild);
        $this->entityManager->flush();

        $result = $this->pageInfoRepository->findChildrenPages(published: false, parentId: $parent->getId());

        $slugs = array_map(fn (PageInfo $page) => $page->getSlug(), $result);

        $this->assertContains($unpublishedChild->getSlug(), $slugs);
    }

    public function testFindChildrenPagesReturnsAllChildrenWhenParentIdIsNull(): void
    {
        $parent1 = $this->createPage('parent-a', 'parent-a', PageCategory::CAREER, null);
        $parent2 = $this->createPage('parent-b', 'parent-b', PageCategory::CAREER, null);
        $child1  = $this->createPage('child-of-a', 'child-of-a', PageCategory::CAREER, $parent1);
        $child2  = $this->createPage('child-of-b', 'child-of-b', PageCategory::CAREER, $parent2);

        $this->entityManager->persist($parent1);
        $this->entityManager->persist($parent2);
        $this->entityManager->persist($child1);
        $this->entityManager->persist($child2);
        $this->entityManager->flush();

        $result = $this->pageInfoRepository->findChildrenPages(published: true, parentId: null);

        $slugs = array_map(fn (PageInfo $page) => $page->getSlug(), $result);

        $this->assertContains($child1->getSlug(), $slugs);
        $this->assertContains($child2->getSlug(), $slugs);
    }

    public function testFindChildrenPagesReturnsEmptyArrayWhenParentHasNoChildren(): void
    {
        $parent = $this->createPage('lonely-parent', 'lonely-parent', PageCategory::CAREER, null);

        $this->entityManager->persist($parent);
        $this->entityManager->flush();

        $result = $this->pageInfoRepository->findChildrenPages(published: true, parentId: $parent->getId());

        $this->assertCount(0, $result);
    }

    private function createPage(
        string $title,
        string $slug,
        PageCategory $category = PageCategory::CAREER,
        ?PageInfo $parent = null,
        bool $published = true,
    ): PageInfo {
        return (new PageInfo())
            ->setTitle($title)
            ->setTechnicalName($slug)
            ->setSlug($slug)
            ->setTagline($title)
            ->setSubtitle($title)
            ->setQuote($title)
            ->setPosition(1)
            ->setInHeader(true)
            ->setCategory($category)
            ->setParent($parent)
            ->setPublished($published);
    }

    /**
     * Clean up the page_info table.
     */
    private function cleanUpDatabase(): void
    {
        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('DELETE FROM page');
        $this->entityManager->clear();
    }
}
