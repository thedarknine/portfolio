<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Trait;

use App\Entity\Traits\LocalizableTrait;
use PHPUnit\Framework\TestCase;

class LocalizableTraitTest extends TestCase
{
    use LocalizableTrait;

    public function testGetCity(): void
    {
        $this->assertEquals(null, $this->getCity());
    }

    public function testSetCity(): void
    {
        $this->setCity('Paris');
        $this->assertEquals('Paris', $this->getCity());
    }

    public function testGetDepartment(): void
    {
        $this->assertEquals(null, $this->getDepartment());
    }

    public function testSetDepartment(): void
    {
        $this->setDepartment(75);
        $this->assertEquals(75, $this->getDepartment());
    }
}
