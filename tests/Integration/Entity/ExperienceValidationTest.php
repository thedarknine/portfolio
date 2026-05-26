<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Entity;

use App\Entity\Company;
use App\Entity\Experience;
use App\Entity\ExperienceItem;
use App\Entity\ExperienceLink;
use App\Entity\Skill;
use App\Entity\SkillType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ExperienceValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    /**
     * Test nominal case:
     * - Experience with valid title, description, start date, and company
     */
    public function testValidExperienceHasNoViolation(): void
    {
        // Create a quick Company instance required for the relation
        $company = new Company();

        $experience = (new Experience())
            ->setTitle('Product Owner Technique')
            ->setSlug('product-owner-technique')
            ->setSummary('Gestion du backlog produit et architecture technique.')
            ->setStartDate(new \DateTime('-1 year'))
            ->setCompany($company);

        $errors = $this->validator->validate($experience);
        $this->assertCount(0, $errors);

        $this->assertSame('Product Owner Technique', $experience->getTitle());
        $this->assertSame('product-owner-technique', $experience->getSlug());
        $this->assertSame('Gestion du backlog produit et architecture technique.', $experience->getSummary());
        $this->assertSame($company, $experience->getCompany());
    }

    /**
     * Test nominal case with description:
     * - Experience with valid title, description, start date, and company
     */
    public function testValidExperienceWithDescriptionHasNoViolation(): void
    {
        $company = new Company();

        $experience = (new Experience())
            ->setTitle('Product Owner Technique')
            ->setSlug('product-owner-technique')
            ->setDescription('Gestion du backlog produit et architecture technique.')
            ->setStartDate(new \DateTime('-1 year'))
            ->setCompany($company);

        $this->assertSame('Gestion du backlog produit et architecture technique.', $experience->getDescription());

        $errors = $this->validator->validate($experience);
        $this->assertCount(0, $errors);
    }

    /**
     * Test subtitle too long trigger violation
     * - Experience with subtitle too long (more than 120 characters).
     */
    public function testSubtitleTooLongTriggerViolation(): void
    {
        $company = new Company();
        $experience = (new Experience())
            ->setTitle('Développeur Symfony')
            ->setSlug('developpeur-symfony')
            ->setDescription('Une description valide')
            ->setCompany($company)
            ->setStartDate(new \DateTime())
            ->setSubtitle(str_repeat('A', 121)); // 121 characters

        $errors = $this->validator->validate($experience);

        // If you have a #[Assert\Length(max: 120)] on your entity or inherited, this test will pass
        $this->assertGreaterThan(0, count($errors));
    }

    /**
     * Test get duration calculates correctly
     * - Experience with start date and end date.
     */
    public function testGetDurationCalculatesCorrectly(): void
    {
        $experience = new Experience();

        // Case 1: An experience that lasted exactly 1 year and 2 months
        $experience->setStartDate(new \DateTime('2024-01-01'));
        $experience->setEndDate(new \DateTime('2025-03-01'));

        $duration = $experience->getDuration();

        $this->assertSame(1, $duration['nbYears']);
        $this->assertSame(2, $duration['nbMonths']);
    }

    /**
     * Test add and remove items relations
     * - Experience with items.
     */
    public function testAddAndRemoveItemsRelations(): void
    {
        $experience = new Experience();
        $item = new ExperienceItem();

        // Test addition (must return static for chaining)
        $result = $experience->addItem($item);

        $this->assertSame($experience, $result);
        $this->assertTrue($experience->getItems()->contains($item));
        $this->assertSame($experience, $item->getExperience()); // Verify that the inverse side has been hydrated

        // Test removal
        $experience->removeItem($item);
        $this->assertFalse($experience->getItems()->contains($item));
    }

    /**
     * Test that end date cannot be before start date.
     */
    public function testEndDateBeforeStartDateTriggerViolation(): void
    {
        $experience = (new Experience())
            ->setTitle('Developer')
            ->setDescription('Valid description')
            ->setCompany(new Company())
            ->setStartDate(new \DateTime('2026-05-01'))
            ->setEndDate(new \DateTime('2026-01-01'));

        $errors = $this->validator->validate($experience);

        $this->assertGreaterThan(0, count($errors));
    }

    /**
     * Test that invalid skill in collection triggers violation
     * - Experience with invalid skill in collection.
     */
    public function testInvalidSkillInCollectionTriggersViolation(): void
    {
        // 1. Create a valid experience as base
        $experience = (new Experience())
            ->setTitle('Développeur Full-Stack')
            ->setDescription('Description valide')
            ->setCompany(new Company())
            ->setStartDate(new \DateTime());

        // 2. Create an invalid skill (empty name)
        $invalidSkill = new Skill();
        $invalidSkill->setName('');
        $invalidSkill->setSlug('');
        $invalidSkill->setStartYear(2016);
        $invalidSkill->setEndYear(2022);
        $invalidSkill->setLevel(85);
        $invalidSkill->setSkillType(
            (new SkillType())
                ->setName('Programming')
                ->setSlug('programming')
                ->setPosition(1)
        );

        // 3. Associate the invalid skill with the experience
        $experience->addSkill($invalidSkill);

        // 4. Validate the parent entity (Experience)
        $errors = $this->validator->validate($experience);

        // 5. Expect at least one error
        $this->assertGreaterThan(0, count($errors));

        // Check that the error comes from the nested property
        // Symfony uses dot notation for nested properties: 'skills[0].name'
        $this->assertSame('skills[0].name', $errors[0]->getPropertyPath());
        $this->assertSame('Name cannot be empty.', $errors[0]->getMessage());
    }

    /**
     * Test adding and removing skills from collection.
     */
    public function testAddAndRemoveSkillsRelations(): void
    {
        $experience = new Experience();
        $skill = new Skill();

        // Test adding skill
        $experience->addSkill($skill);
        $this->assertTrue($experience->getSkills()->contains($skill));

        $result = $experience->removeSkill($skill);

        $this->assertSame($experience, $result);
        $this->assertFalse($experience->getSkills()->contains($skill));
    }

    /**
     * Test adding and removing links from collection.
     */
    public function testAddAndRemoveLinksRelations(): void
    {
        $experience = new Experience();
        $link = new ExperienceLink();

        // Test adding link
        $experience->addLink($link);
        $this->assertTrue($experience->getLinks()->contains($link));

        $result = $experience->removeLink($link);

        $this->assertSame($experience, $result);
        $this->assertFalse($experience->getLinks()->contains($link));
    }

    /**
     * Test get ID via Reflection to ensure 100% coverage.
     */
    public function testGetId(): void
    {
        $experience = new Experience();

        $reflection = new \ReflectionClass($experience);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($experience, 7);

        $this->assertSame(7, $experience->getId());
    }
}
