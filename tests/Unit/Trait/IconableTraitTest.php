<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Trait;

use App\Entity\Traits\IconableTrait;
use PHPUnit\Framework\TestCase;

class IconableTraitTest extends TestCase
{
    /**
     * Test that the icon is initialized to null by default.
     */
    public function testDefaultIconIsNull(): void
    {
        $dummyEntity = new class {
            use IconableTrait;
        };

        $this->assertNull($dummyEntity->getIcon());
    }

    /**
     * Test getters and setters and fluent interface (chaining).
     */
    public function testGetAndSetIcon(): void
    {
        $dummyEntity = new class {
            use IconableTrait;
        };

        $iconClass = 'fa-solid fa-code';

        // 1. Test the setter and get its return value
        $result = $dummyEntity->setIcon($iconClass);

        // 2. Verify that the getter returns the correct string
        $this->assertSame($iconClass, $dummyEntity->getIcon());

        // 3. IMPORTANT : Verify that the setter returns the object instance ($this)
        // This validates the "static" return type contract
        $this->assertSame($dummyEntity, $result);
    }
}
