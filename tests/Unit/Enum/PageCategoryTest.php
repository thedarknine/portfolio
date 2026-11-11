<?php

declare(strict_types=1);

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Tests\Unit\Enum;

use App\Enum\PageCategory;
use PHPUnit\Framework\TestCase;

class PageCategoryTest extends TestCase
{
    /**
     * Test that the enum values are correctly defined.
     */
    public function testEnumValues(): void
    {
        $this->assertEquals('career', PageCategory::CAREER->value);
        $this->assertEquals('interest', PageCategory::INTEREST->value);
        $this->assertEquals('about', PageCategory::ABOUT->value);
    }

    /**
     * Test the getLabel method for each enum value.
     */
    public function testGetLabel(): void
    {
        $this->assertEquals('Parcours', PageCategory::CAREER->getLabel());
        $this->assertEquals('Centres d\'intérêt', PageCategory::INTEREST->getLabel());
        $this->assertEquals('À propos', PageCategory::ABOUT->getLabel());
    }
}
