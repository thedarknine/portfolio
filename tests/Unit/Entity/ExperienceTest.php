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
use App\Entity\ExperienceItem;
use App\Entity\ExperienceLink;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class ExperienceTest extends TestCase
{
    /**
     * Relationship Experience <-> ExperienceItem.
     */
    public function testRemoveItemClearsRelation(): void
    {
        $experience = new Experience();
        $item = new ExperienceItem();

        $item->setExperience($experience);
        if (method_exists($experience, 'addItem')) {
            $experience->addItem($item);
        }

        $this->assertSame($experience, $item->getExperience());

        $experience->removeItem($item);
        $this->assertNull($item->getExperience(), 'L\'item ne devrait plus être lié à l\'expérience.');
    }

    /**
     * OneToMany/ManyToOne Relation Experience <-> ExperienceLink.
     */
    public function testAddAndRemoveLinkMaintainBidirectionalRelation(): void
    {
        $experience = new Experience();
        $link = new ExperienceLink();

        // Add test
        $experience->addLink($link);
        $this->assertTrue($experience->getLinks()->contains($link));
        $this->assertSame($experience, $link->getExperience(), 'Le lien aurait dû recevoir l\'instance de l\'expérience.');

        // Remove test
        $experience->removeLink($link);
        $this->assertFalse($experience->getLinks()->contains($link));
        $this->assertNull($link->getExperience());
    }

    /**
     * Experience dates validation.
     */
    public function testValidateDatesWithDifferentScenarios(): void
    {
        // --- VALID CASES (No violation expected) ---
        $contextMock = $this->createMock(ExecutionContextInterface::class);
        $contextMock->expects($this->never())->method('buildViolation');

        $experience = new Experience();

        // Start date only (mission in progress)
        $experience->setStartDate(new \DateTime('2026-01-01'))->setEndDate(null);
        $experience->validateDates($contextMock);

        // Valid date range
        $experience->setStartDate(new \DateTime('2026-01-01'))->setEndDate(new \DateTime('2026-05-01'));
        $experience->validateDates($contextMock);

        // Same start and end date (valid)
        $experience->setStartDate(new \DateTime('2026-01-01'))->setEndDate(new \DateTime('2026-01-01'));
        $experience->validateDates($contextMock);

        // --- INVALID CASES (Violation expected) ---
        $invalidContextMock = $this->createMock(ExecutionContextInterface::class);
        $violationBuilderMock = $this->createMock(ConstraintViolationBuilderInterface::class);

        $invalidContextMock->expects($this->once())
            ->method('buildViolation')
            ->with('The end date cannot be before the start date.')
            ->willReturn($violationBuilderMock);

        $violationBuilderMock->expects($this->once())
            ->method('atPath')
            ->with('endDate')
            ->willReturn($violationBuilderMock);

        $violationBuilderMock->expects($this->once())
            ->method('addViolation');

        // Start date after end date
        $experience->setStartDate(new \DateTime('2026-06-01'))->setEndDate(new \DateTime('2026-01-01'));
        $experience->validateDates($invalidContextMock);
    }
}
