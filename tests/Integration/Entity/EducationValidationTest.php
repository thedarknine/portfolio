<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is proprietary, all rights reserved.
 * See the LICENSE file bundled with this source code.
 */

namespace App\Tests\Integration\Entity;

use App\Entity\Education;
use App\Entity\School;
use App\Enum\EducationType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EducationValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    /**
     * Nominal case: a valid education passes without violations.
     */
    public function testValidEducationHasNoViolation(): void
    {
        $enumCases   = EducationType::cases();
        $defaultType = $enumCases[0];

        $education = new Education()
            ->setTitle('DEUG MIAS')
            ->setSlug('deug-mias')
            ->setYear(2002)
            ->setDetails('Mathématiques et Informatique Appliquées aux Sciences')
            ->setSpeciality('Algèbre et développement')
            ->setMention('Assez bien')
            ->setSchool(
                new School()
                    ->setName('Université de Paris')
                    ->setSlug('universite-de-paris')
                    ->setCity('Paris')
                    ->setDepartment(75),
            )
            ->setType($defaultType);

        $errors = $this->validator->validate($education);
        $this->assertCount(0, $errors);

        $this->assertSame('Assez bien', $education->getMention());
        $this->assertSame('Algèbre et développement', $education->getSpeciality());
        $this->assertSame($defaultType, $education->getType());
        $this->assertSame('DEUG MIAS', $education->getTitle());
        $this->assertSame('deug-mias', $education->getSlug());
        $this->assertSame(2002, $education->getYear());
        $this->assertSame('Mathématiques et Informatique Appliquées aux Sciences', $education->getDetails());
    }

    /**
     * Test that details too long trigger a violation.
     */
    public function testDetailsTooLongTriggerViolation(): void
    {
        $education = new Education()
            ->setTitle('Licence')
            ->setSlug('licence')
            ->setYear(2026)
            ->setDetails(str_repeat('A', 181))
            ->setSchool(new School());

        $errors = $this->validator->validate($education);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('details', $errors[0]->getPropertyPath());
    }

    /**
     * Test that invalid year triggers a violation.
     */
    public function testInvalidYearTriggerViolation(): void
    {
        $education = new Education()
            ->setTitle('Master')
            ->setYear(1989)
            ->setDetails('Un cursus valide')
            ->setSchool(new School());

        $errors = $this->validator->validate($education);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('year', $errors[0]->getPropertyPath());
    }

    /**
     * A takeaway and a complete link are accepted.
     */
    public function testTakeawayAndLinkHaveNoViolation(): void
    {
        $education = $this->createValidEducation()
            ->setTakeaway('Recherche utilisateur, prototypage et tests.')
            ->setLinkLabel('Voir l’étude de cas')
            ->setLinkUrl('/experience/po-coffreo/formation-product-designer');

        $this->assertCount(0, $this->validator->validate($education));
        $this->assertSame('Recherche utilisateur, prototypage et tests.', $education->getTakeaway());
        $this->assertSame('Voir l’étude de cas', $education->getLinkLabel());
    }

    /**
     * A link label without URL is rejected.
     */
    public function testLinkLabelWithoutUrlTriggerViolation(): void
    {
        $education = $this->createValidEducation()->setLinkLabel('Voir le stage');

        $errors = $this->validator->validate($education);

        $this->assertCount(1, $errors);
        $this->assertSame('linkUrl', $errors[0]->getPropertyPath());
    }

    /**
     * A link URL that is neither absolute nor a site path is rejected.
     */
    public function testInvalidLinkUrlTriggerViolation(): void
    {
        $education = $this->createValidEducation()
            ->setLinkLabel('Voir le stage')
            ->setLinkUrl('experience');

        $errors = $this->validator->validate($education);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('linkUrl', $errors[0]->getPropertyPath());
    }

    /**
     * Test getting the ID via Reflection.
     */
    public function testGetId(): void
    {
        $education = new Education();

        $reflection = new \ReflectionClass($education);
        $property   = $reflection->getProperty('id');
        $property->setValue($education, 45);

        $this->assertSame(45, $education->getId());
    }

    /**
     * Build a valid education to test the optional takeaway and link.
     */
    private function createValidEducation(): Education
    {
        return new Education()
            ->setTitle('Product Designer')
            ->setSlug('product-designer')
            ->setYear(2021)
            ->setDetails('Formation Advanced')
            ->setSchool(
                new School()
                    ->setName('The Design Crew')
                    ->setSlug('the-design-crew')
                    ->setCity('Paris')
                    ->setDepartment(75),
            )
            ->setType(EducationType::cases()[0]);
    }
}
