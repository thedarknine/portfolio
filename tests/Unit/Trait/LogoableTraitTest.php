<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Trait;

use App\Entity\Traits\LogoableTrait;
use PHPUnit\Framework\TestCase;

class LogoableTraitTest extends TestCase
{
    /**
     * Test that the logo is initialized to null by default.
     */
    public function testDefaultLogoIsNull(): void
    {
        $dummyEntity = new class {
            use LogoableTrait;
        };

        $this->assertNull($dummyEntity->getLogo());
    }

    /**
     * Test getters, setters and fluent interface (chaining).
     */
    public function testGetAndSetLogo(): void
    {
        $dummyEntity = new class {
            use LogoableTrait;
        };

        $logoPath = 'images/logos/symfony.svg';

        // 1. Apply the setter and store the return value
        $result = $dummyEntity->setLogo($logoPath);

        // 2. Validate that the getter returns the correct string
        $this->assertSame($logoPath, $dummyEntity->getLogo());

        // 3. Validate the fluent interface: the setter must return the object itself
        $this->assertSame($dummyEntity, $result);
    }
}
