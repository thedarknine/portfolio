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

use App\Enum\EducationType;
use PHPUnit\Framework\TestCase;

class EducationTypeTest extends TestCase
{
    /**
     * Test that the enum values are correctly defined.
     */
    public function testEnumValues(): void
    {
        $this->assertEquals('universitaire', EducationType::UNIVERSITARY->value);
        $this->assertEquals('professionnel', EducationType::PROFESSIONAL->value);
    }

    /**
     * Test the getLabel method for each enum value.
     */
    public function testGetLabel(): void
    {
        $this->assertEquals('Universitaire', EducationType::UNIVERSITARY->getLabel());
        $this->assertEquals('Professionnel', EducationType::PROFESSIONAL->getLabel());
    }
}
