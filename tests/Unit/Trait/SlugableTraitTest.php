<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Trait;

use App\Entity\Traits\SlugableTrait;
use PHPUnit\Framework\TestCase;

class SlugableTraitTest extends TestCase
{
    /**
     * Test that the slug is initialized to null by default.
     */
    public function testDefaultSlugIsNull(): void
    {
        $dummyEntity = new class {
            use SlugableTrait;
        };

        $this->assertNull($dummyEntity->getSlug());
    }

    /**
     * Test getters, setters and fluent interface (chaining).
     */
    public function testGetAndSetSlug(): void
    {
        $dummyEntity = new class {
            use SlugableTrait;
        };

        $slugValue = 'super-projet-retro';

        // Test the setter and fluent chaining
        $result = $dummyEntity->setSlug($slugValue);

        $this->assertSame($slugValue, $dummyEntity->getSlug());
        $this->assertSame($dummyEntity, $result);
    }
}
