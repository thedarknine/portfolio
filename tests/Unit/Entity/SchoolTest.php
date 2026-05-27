<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Entity;

use App\Entity\Education;
use App\Entity\School;
use PHPUnit\Framework\TestCase;

class SchoolTest extends TestCase
{
    public function testRemoveEducationClearsRelation(): void
    {
        $school = new School();
        $education = new Education();

        // 1. Establish relation in both directions
        $education->setSchool($school);

        if (method_exists($school, 'addEducation')) {
            $school->addEducation($education);
        }

        $this->assertSame($school, $education->getSchool());

        // 2. Remove education from school
        $school->removeEducation($education);

        // 3. CRITICAL ASSERTION: The relation must be broken from the education side
        // This assertion will fail if the mutant from Infection is not caught
        $this->assertNull($education->getSchool(), 'The education should not be linked to the school anymore.');
    }
}
