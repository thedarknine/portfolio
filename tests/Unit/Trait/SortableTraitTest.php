<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Trait;

use App\Entity\Traits\SortableTrait;
use PHPUnit\Framework\TestCase;

class SortableTraitTest extends TestCase
{
    /**
     * Test that the position is initialized to null by default.
     */
    public function testDefaultPositionIsNull(): void
    {
        $dummyEntity = new class {
            use SortableTrait;
        };

        $this->assertNull($dummyEntity->getPosition());
    }

    /**
     * Test getters, setters and fluent interface (chaining).
     */
    public function testGetAndSetPosition(): void
    {
        $dummyEntity = new class {
            use SortableTrait;
        };

        $targetPosition = 42;

        // 1. Apply the setter and retrieve the result to test chaining
        $result = $dummyEntity->setPosition($targetPosition);

        // 2. Verify that the value is correct
        $this->assertSame($targetPosition, $dummyEntity->getPosition());

        // 3. Validate the fluent return (static)
        $this->assertSame($dummyEntity, $result);
    }
}
