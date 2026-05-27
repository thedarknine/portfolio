<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Entity;

use App\Entity\Experience;
use App\Entity\Skill;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class SkillTest extends TestCase
{
    /**
     * Bidirectional ManyToMany relations.
     */
    public function testAddAndRemoveExperienceMaintainBidirectionalRelation(): void
    {
        $skill = new Skill();
        $experience = new Experience();

        // 1. Add test
        $skill->addExperience($experience);
        $this->assertTrue($skill->getExperiences()->contains($experience));
        // If your Experience entity has a getSkills() or contains() method:
        if (method_exists($experience, 'getSkills')) {
            $this->assertTrue($experience->getSkills()->contains($skill), 'The skill should have been added to the experience.');
        }

        // 2. Remove test
        $skill->removeExperience($experience);
        $this->assertFalse($skill->getExperiences()->contains($experience));

        if (method_exists($experience, 'getSkills')) {
            $this->assertFalse(
                $experience->getSkills()->contains($skill),
                'Skill should have been removed from the experience as well.'
            );
        }
    }

    /**
     * Validation of skill years.
     */
    public function testValidateYearsWithDifferentScenarios(): void
    {
        $contextMock = $this->createMock(ExecutionContextInterface::class);
        // Expect buildViolation to NEVER be called for valid cases
        $contextMock->expects($this->never())->method('buildViolation');

        $skill = new Skill();

        // Only one is null
        $skill->setStartYear(2026)->setEndYear(null);
        $skill->validateYears($contextMock, null);

        // Equal years are valid
        $skill->setStartYear(2026)->setEndYear(2026);
        $skill->validateYears($contextMock, null);

        // Invalid case (to validate that the error is triggered)
        $invalidContextMock = $this->createMock(ExecutionContextInterface::class);
        $violationBuilderMock = $this->createMock(ConstraintViolationBuilderInterface::class);

        // Simulate Symfony's validation chaining
        $invalidContextMock->expects($this->once())
            ->method('buildViolation')
            ->with('The end date cannot be before the start date.')
            ->willReturn($violationBuilderMock);

        $violationBuilderMock->expects($this->once())
            ->method('atPath')
            ->with('endYear')
            ->willReturn($violationBuilderMock);

        $violationBuilderMock->expects($this->once())
            ->method('addViolation');

        // The end year is before the start year -> Should trigger the mock
        $skill->setStartYear(2026)->setEndYear(2025);
        $skill->validateYears($invalidContextMock, null);
    }
}
