<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Trait;

use App\Entity\Traits\TitleableTrait;
use PHPUnit\Framework\TestCase;

class TitleableTraitTest extends TestCase
{
    /**
     * Test that the title is initialized to null by default.
     */
    public function testDefaultTitleIsNull(): void
    {
        $dummyEntity = new class {
            use TitleableTrait;
        };

        $this->assertNull($dummyEntity->getTitle());
    }

    /**
     * Test getters, setters and fluent interface (chaining).
     */
    public function testGetAndSetTitle(): void
    {
        $dummyEntity = new class {
            use TitleableTrait;
        };

        $titleValue = 'Super Projet Retro';

        // Test the setter and static chaining
        $result = $dummyEntity->setTitle($titleValue);

        $this->assertSame($titleValue, $dummyEntity->getTitle());
        $this->assertSame($dummyEntity, $result);
    }

    /**
     * Test the magic __toString() method when the title is set.
     */
    public function testToStringReturnsTitleWhenSet(): void
    {
        $dummyEntity = new class {
            use TitleableTrait;
        };

        $dummyEntity->setTitle('Portfolio v4');

        // Cast explicit to string or use in a string to test __toString
        $this->assertSame('Portfolio v4', (string) $dummyEntity);
    }

    /**
     * Test the security of __toString() if the title is null (initial case).
     * Crucial for avoiding EasyAdmin exceptions on newly created objects.
     */
    public function testToStringReturnsEmptyStringWhenTitleIsNull(): void
    {
        $dummyEntity = new class {
            use TitleableTrait;
        };

        $this->assertSame('', (string) $dummyEntity);
    }
}
