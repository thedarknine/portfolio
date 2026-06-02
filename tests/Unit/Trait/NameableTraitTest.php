<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Trait;

use App\Entity\Traits\NameableTrait;
use PHPUnit\Framework\TestCase;

class NameableTraitTest extends TestCase
{
    /**
     * Test that the name is initialized to null by default.
     */
    public function testDefaultNameIsNull(): void
    {
        $dummyEntity = new class {
            use NameableTrait;
        };

        $this->assertNull($dummyEntity->getName());
    }

    /**
     * Test getters, setters and fluent interface (chaining).
     */
    public function testGetAndSetName(): void
    {
        $dummyEntity = new class {
            use NameableTrait;
        };

        $nameValue = 'Super Projet Retro';

        // Test the setter and static chaining
        $result = $dummyEntity->setName($nameValue);

        $this->assertSame($nameValue, $dummyEntity->getName());
        $this->assertSame($dummyEntity, $result);
    }

    /**
     * Test the magic __toString() method when the name is set.
     */
    public function testToStringReturnsNameWhenSet(): void
    {
        $dummyEntity = new class {
            use NameableTrait;
        };

        $dummyEntity->setName('Portfolio v4');

        // Cast explicit to string or use in a string to test __toString
        $this->assertSame('Portfolio v4', (string) $dummyEntity);
    }

    /**
     * Test the security of __toString() if the name is null (initial case).
     * Crucial for avoiding EasyAdmin exceptions on newly created objects.
     */
    public function testToStringReturnsEmptyStringWhenNameIsNull(): void
    {
        $dummyEntity = new class {
            use NameableTrait;
        };

        $this->assertSame('', (string) $dummyEntity);
    }
}
