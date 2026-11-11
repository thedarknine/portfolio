<?php

declare(strict_types=1);

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Tests\Unit\Entity;

use App\Entity\Company;
use App\Entity\Experience;
use App\Entity\Skill;
use App\Entity\SkillType;
use PHPUnit\Framework\TestCase;

class CompanyTest extends TestCase
{
    public function testRemoveExperienceClearsRelation(): void
    {
        $company    = new Company();
        $experience = new Experience();

        // 1. Establish relation in both directions
        $experience->setCompany($company);

        // Force add to company's collection if entity doesn't do it in setCompany
        $company->addExperience($experience);

        $this->assertSame($company, $experience->getCompany());

        // 2. Remove experience from company
        $company->removeExperience($experience);

        // 3. CRITICAL ASSERTION: The relation must be broken from the experience side
        // This assertion will fail if the mutant from Infection is not caught
        $this->assertNull($experience->getCompany(), 'The experience should not be linked to the company anymore.');
    }

    public function testSkillTypeRemoveSkillClearsRelation(): void
    {
        $type  = new SkillType();
        $skill = new Skill();

        $skill->setSkillType($type);
        $type->addSkill($skill);

        $type->removeSkill($skill);

        $this->assertNull($skill->getSkillType(), 'Skill should not be linked to the type anymore.');
    }
}
