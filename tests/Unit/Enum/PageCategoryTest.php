<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
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
    }

    /**
     * Test the getLabel method for each enum value.
     */
    public function testGetLabel(): void
    {
        $this->assertEquals('Parcours', PageCategory::CAREER->getLabel());
        $this->assertEquals('Centres d\'intérêt', PageCategory::INTEREST->getLabel());
    }
}
