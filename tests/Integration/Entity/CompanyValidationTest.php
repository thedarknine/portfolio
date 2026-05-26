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
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CompanyValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    /**
     * Nominal case : company with all required fields is valid.
     */
    public function testValidCompanyHasNoViolation(): void
    {
        $company = (new Company())
            ->setName('Mon Entreprise Retro')
            ->setSlug('mon-entreprise-retro')
            ->setCity('Orléans')
            ->setDepartment(45)
            ->setUrl('https://carolinenoyer.fr');

        $errors = $this->validator->validate($company);
        $this->assertCount(0, $errors);

        $this->assertSame('Mon Entreprise Retro', $company->getName());
        $this->assertSame('mon-entreprise-retro', $company->getSlug());
        $this->assertSame('Orléans', $company->getCity());
        $this->assertSame(45, $company->getDepartment());
        $this->assertSame('https://carolinenoyer.fr', $company->getUrl());
    }

    /**
     * Test city constraint: city is required and limited in size.
     */
    public function testCityConstraints(): void
    {
        // Case 1: Empty city
        $company = (new Company())->setCity('')->setDepartment(45);
        $errors = $this->validator->validate($company);
        $this->assertGreaterThan(0, count($errors));

        // Case 2: City too long
        $companyLong = (new Company())->setCity(str_repeat('X', 101))->setDepartment(45);
        $errorsLong = $this->validator->validate($companyLong);
        $this->assertGreaterThan(0, count($errorsLong));
        $this->assertSame('city', $errorsLong[0]->getPropertyPath());
    }

    /**
     * Test department constraints: department is required and must be positive.
     */
    public function testDepartmentConstraints(): void
    {
        // Case 1: Department out of bounds (e.g., 9999)
        $company = (new Company())->setCity('Paris')->setDepartment(9999);
        $errors = $this->validator->validate($company);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('department', $errors[0]->getPropertyPath());

        // Case 2: Negative department
        $companyNeg = (new Company())->setCity('Paris')->setDepartment(-5);
        $errorsNeg = $this->validator->validate($companyNeg);
        $this->assertGreaterThan(0, count($errorsNeg));
    }

    /**
     * Test URL constraint: URL must be valid if provided.
     */
    public function testUrlConstraint(): void
    {
        $company = (new Company())
            ->setCity('Lyon')
            ->setDepartment(69)
            ->setUrl('pas-une-url-valide');

        $errors = $this->validator->validate($company);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('url', $errors[0]->getPropertyPath());
    }

    /**
     * Test add and remove methods of the experiences collection.
     */
    public function testAddAndRemoveExperiencesRelations(): void
    {
        $company = new Company();
        $experience = new Experience();

        // Add an experience
        $resultAdd = $company->addExperience($experience);
        $this->assertSame($company, $resultAdd); // Fluent interface
        $this->assertTrue($company->getExperiences()->contains($experience));
        $this->assertSame($company, $experience->getCompany());

        // Remove the experience
        $resultRemove = $company->removeExperience($experience);
        $this->assertSame($company, $resultRemove);
        $this->assertFalse($company->getExperiences()->contains($experience));
    }

    /**
     * Test get ID via Reflection to ensure 100% coverage.
     */
    public function testGetId(): void
    {
        $company = new Company();

        $reflection = new \ReflectionClass($company);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($company, 7);

        $this->assertSame(7, $company->getId());
    }
}
