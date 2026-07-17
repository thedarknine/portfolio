<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Entity;

use App\Entity\PageInfo;
use PHPUnit\Framework\TestCase;

class PageInfoTest extends TestCase
{
    public function testAddChildSetsBothSidesOfRelationship(): void
    {
        $parent = new PageInfo();
        $child  = new PageInfo();

        $parent->addChild($child);

        $this->assertSame($parent, $child->getParent());
        $this->assertCount(1, $parent->getChildren());
        $this->assertTrue($parent->getChildren()->contains($child));
    }

    public function testAddChildDoesNotDuplicateChild(): void
    {
        $parent = new PageInfo();
        $child  = new PageInfo();

        $parent->addChild($child);
        $parent->addChild($child);

        $this->assertCount(1, $parent->getChildren());
    }

    public function testRemoveChildRemovesRelationshipOnBothSides(): void
    {
        $parent = new PageInfo();
        $child  = new PageInfo();

        $parent->addChild($child);

        $parent->removeChild($child);

        $this->assertCount(0, $parent->getChildren());
        $this->assertNull($child->getParent());
    }

    public function testRemoveUnknownChildDoesNothing(): void
    {
        $parent = new PageInfo();
        $child  = new PageInfo();

        $parent->removeChild($child);

        $this->assertNull($child->getParent());
        $this->assertCount(0, $parent->getChildren());
    }

    public function testIsRoot(): void
    {
        $parent = new PageInfo();
        $child  = new PageInfo();

        $this->assertTrue($parent->isRoot());

        $child->setParent($parent);

        $this->assertFalse($child->isRoot());
    }

    public function testHasChildren(): void
    {
        $parent = new PageInfo();

        $this->assertFalse($parent->hasChildren());

        $parent->addChild(new PageInfo());

        $this->assertTrue($parent->hasChildren());
    }

    public function testGetPublishedChildrenReturnsOnlyPublishedChildren(): void
    {
        $parent = new PageInfo();

        $published = (new PageInfo())
            ->setPublished(true);

        $draft = (new PageInfo())
            ->setPublished(false);

        $parent->addChild($published);
        $parent->addChild($draft);

        $children = $parent->getPublishedChildren();

        $this->assertCount(1, $children);
        $this->assertSame($published, $children[0]);
    }
}
