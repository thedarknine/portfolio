<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Entity;

use App\Entity\Education;
use App\Entity\School;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SchoolValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    /**
     * Nominal case: A school with valid data passes validation.
     */
    public function testValidSchoolHasNoViolation(): void
    {
        $school = (new School())
            ->setName('Université d’Orléans')
            ->setSlug('universite-d-orleans')
            ->setCity('Orléans')
            ->setDepartment(45);

        $errors = $this->validator->validate($school);
        $this->assertCount(0, $errors);

        $this->assertSame('Université d’Orléans', $school->getName());
        $this->assertSame('universite-d-orleans', $school->getSlug());
        $this->assertSame('Orléans', $school->getCity());
        $this->assertSame(45, $school->getDepartment());
    }

    /**
     * Test the Education collection management (add/remove).
     */
    public function testEducationCollectionManagement(): void
    {
        $school    = new School();
        $education = new Education();

        $this->assertCount(0, $school->getEducation());

        $school->addEducation($education);
        $this->assertCount(1, $school->getEducation());
        $this->assertSame($school, $education->getSchool());

        $school->removeEducation($education);
        $this->assertCount(0, $school->getEducation());
    }

    /**
     * Test that an empty or too long city triggers a violation.
     */
    public function testInvalidCityTriggerViolations(): void
    {
        $school = (new School())
            ->setName('École Test')
            ->setSlug('ecole-test')
            ->setCity('')
            ->setDepartment(45);

        $errors = $this->validator->validate($school);
        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('city', $errors[0]->getPropertyPath());
    }

    /**
     * Test that an invalid department number triggers a violation.
     */
    public function testInvalidDepartmentTriggerViolation(): void
    {
        $school = (new School())
            ->setName('École Test')
            ->setSlug('ecole-test')
            ->setCity('Paris')
            ->setDepartment(999);

        $errors = $this->validator->validate($school);
        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('department', $errors[0]->getPropertyPath());
    }

    /**
     * Test the ID via Reflection.
     */
    public function testGetId(): void
    {
        $school = new School();

        $reflection = new \ReflectionClass($school);
        $property   = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($school, 45);

        $this->assertSame(45, $school->getId());
    }
}
