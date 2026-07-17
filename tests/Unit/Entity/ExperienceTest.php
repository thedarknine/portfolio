<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Entity;

use App\Entity\Company;
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
        $item       = new ExperienceItem();

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
        $link       = new ExperienceLink();

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
        $invalidContextMock   = $this->createMock(ExecutionContextInterface::class);
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

    /**
     * Test getDisplayName returns formatted string with title, company, and year.
     */
    public function testGetDisplayNameWithAllData(): void
    {
        $company    = (new Company())->setName('Tech Corp');
        $experience = (new Experience())
            ->setTitle('Senior Developer')
            ->setStartDate(new \DateTime('2026-06-15'))
            ->setCompany($company);

        $displayName = $experience->getDisplayName();

        $this->assertSame('Senior Developer - Tech Corp (2026)', $displayName);
    }

    /**
     * Test getDisplayName with different years.
     */
    public function testGetDisplayNameWithDifferentYears(): void
    {
        $company = (new Company())->setName('Old Corp');

        // Test with 2000
        $experience2000 = (new Experience())
            ->setTitle('Dev Junior')
            ->setStartDate(new \DateTime('2000-03-20'))
            ->setCompany($company);

        $this->assertSame('Dev Junior - Old Corp (2000)', $experience2000->getDisplayName());

        // Test with 2024
        $experience2024 = (new Experience())
            ->setTitle('Dev Senior')
            ->setStartDate(new \DateTime('2024-12-25'))
            ->setCompany($company);

        $this->assertSame('Dev Senior - Old Corp (2024)', $experience2024->getDisplayName());
    }

    /**
     * Test getDisplayName with special characters in title and company name.
     */
    public function testGetDisplayNameWithSpecialCharacters(): void
    {
        $company    = (new Company())->setName('L\'Entreprise & Co.');
        $experience = (new Experience())
            ->setTitle('Chef de Projet C++')
            ->setStartDate(new \DateTime('2020-07-01'))
            ->setCompany($company);

        $displayName = $experience->getDisplayName();

        $this->assertSame('Chef de Projet C++ - L\'Entreprise & Co. (2020)', $displayName);
    }

    /**
     * Test getDisplayName format consistency.
     */
    public function testGetDisplayNameFormatConsistency(): void
    {
        $company    = (new Company())->setName('Company Name');
        $experience = (new Experience())
            ->setTitle('Position Title')
            ->setStartDate(new \DateTime('2023-06-15'))
            ->setCompany($company);

        $displayName = $experience->getDisplayName();

        // Verify the format is: Title - Company (Year)
        $this->assertMatchesRegularExpression(
            '/^Position Title - Company Name \(2023\)$/',
            $displayName,
        );
    }

    /**
     * Test getDisplayName returns formatted string with title, company, and year.
     */
    public function testGetDisplayNameWithValidData(): void
    {
        $company    = (new Company())->setName('Tech Corp');
        $experience = (new Experience())
            ->setTitle('Senior Developer')
            ->setStartDate(new \DateTime('2026-06-15'))
            ->setCompany($company);

        $displayName = $experience->getDisplayName();

        $this->assertSame('Senior Developer - Tech Corp (2026)', $displayName);
    }

    /**
     * Test getDisplayName with different company names.
     */
    public function testGetDisplayNameWithDifferentCompanies(): void
    {
        $experiences = [
            [
                'title'   => 'Dev Junior',
                'company' => 'StartUp Inc',
                'year'    => '2018',
            ],
            [
                'title'   => 'Dev Senior',
                'company' => 'Big Corp',
                'year'    => '2022',
            ],
            [
                'title'   => 'Product Manager',
                'company' => 'Tech Solutions',
                'year'    => '2024',
            ],
        ];

        foreach ($experiences as $data) {
            $company    = (new Company())->setName($data['company']);
            $experience = (new Experience())
                ->setTitle($data['title'])
                ->setStartDate(new \DateTime($data['year'] . '-01-01'))
                ->setCompany($company);

            $expected = sprintf('%s - %s (%s)', $data['title'], $data['company'], $data['year']);
            $this->assertSame($expected, $experience->getDisplayName());
        }
    }

    /**
     * Test getDisplayName with null company throws RuntimeException.
     */
    public function testGetDisplayNameWithNullCompanyThrowsException(): void
    {
        $experience = (new Experience())
            ->setTitle('Freelancer Position')
            ->setStartDate(new \DateTime('2025-01-01'))
            ->setCompany(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Experience must have a company to generate display name');

        $experience->getDisplayName();
    }

    /**
     * Test getDisplayName with long title and company name.
     */
    public function testGetDisplayNameWithLongTitles(): void
    {
        $company    = (new Company())->setName('International Software Development Company LLC');
        $experience = (new Experience())
            ->setTitle('Senior Lead Full-Stack Software Engineer')
            ->setStartDate(new \DateTime('2022-03-20'))
            ->setCompany($company);

        $displayName = $experience->getDisplayName();

        $this->assertSame(
            'Senior Lead Full-Stack Software Engineer - International Software Development Company LLC (2022)',
            $displayName,
        );
    }
}
