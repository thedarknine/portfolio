<?php

declare(strict_types=1);

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
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
