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
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ExperienceItemValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    /**
     * Helper to create a valid Experience entity for testing purposes.
     */
    private function createValidMockExperience(): Experience
    {
        return (new Experience())
            ->setTitle('Product Owner')
            ->setSlug('product-owner')
            ->setDescription('Une description d’expérience valide.')
            ->setStartDate(new \DateTime('-1 year'))
            ->setCompany(new Company());
    }

    /**
     * Nominal case: a valid experience item passes without violations.
     */
    public function testValidExperienceItemHasNoViolation(): void
    {
        $experienceItem = (new ExperienceItem())
            ->setTitle('Développement Backend')
            ->setDetails('Conception d’API REST et optimisation des requêtes de la base de données.')
            ->setPosition(0)
            ->setPicto('fa-code')
            ->setExperience($this->createValidMockExperience());

        $errors = $this->validator->validate($experienceItem);
        $this->assertCount(0, $errors);

        $this->assertSame('Développement Backend', $experienceItem->getTitle());
        $this->assertSame('Conception d’API REST et optimisation des requêtes de la base de données.', $experienceItem->getDetails());
        $this->assertSame(0, $experienceItem->getPosition());
        $this->assertSame('fa-code', $experienceItem->getPicto());
    }

    /**
     * Test that details cannot be empty.
     */
    public function testBlankDetailsTriggerViolation(): void
    {
        $experienceItem = (new ExperienceItem())
            ->setTitle('Item sans détails')
            ->setDetails('')
            ->setPosition(1)
            ->setPicto('fa-bug')
            ->setExperience($this->createValidMockExperience());

        $errors = $this->validator->validate($experienceItem);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('details', $errors[0]->getPropertyPath());
    }

    /**
     * Test that position cannot be negative.
     */
    public function testNegativePositionTriggerViolation(): void
    {
        $experienceItem = (new ExperienceItem())
            ->setTitle('Item mal rangé')
            ->setDetails('Quelques détails...')
            ->setPosition(-5)
            ->setPicto('fa-sort')
            ->setExperience($this->createValidMockExperience());

        $errors = $this->validator->validate($experienceItem);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('position', $errors[0]->getPropertyPath());
    }

    /**
     * Test getting the ID via Reflection.
     */
    public function testGetId(): void
    {
        $experienceItem = new ExperienceItem();

        $reflection = new \ReflectionClass($experienceItem);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($experienceItem, 99);

        $this->assertSame(99, $experienceItem->getId());
    }
}
