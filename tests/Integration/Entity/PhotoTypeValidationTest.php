<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Entity;

use App\Entity\PhotoType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PhotoTypeValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    /**
     * Nominal case: A photo type correctly configured is valid.
     */
    public function testValidPhotoTypeHasNoViolation(): void
    {
        $photoType = (new PhotoType())
            ->setName('Argentique')
            ->setSlug('argentique')
            ->setPosition(0);

        $errors = $this->validator->validate($photoType);
        $this->assertCount(0, $errors);

        $this->assertSame('Argentique', $photoType->getName());
        $this->assertSame('argentique', $photoType->getSlug());
        $this->assertSame(0, $photoType->getPosition());
    }

    /**
     * Test that the name (and by extension the slug) cannot be empty.
     */
    public function testBlankNameTriggerViolation(): void
    {
        $photoType = (new PhotoType())
            ->setName('')
            ->setPosition(1);

        $errors = $this->validator->validate($photoType);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('name', $errors[0]->getPropertyPath());
    }

    /**
     * Test the validation of the negative position inherited from the SortableTrait.
     */
    public function testNegativePositionTriggerViolation(): void
    {
        $photoType = (new PhotoType())
            ->setName('Paysages')
            ->setSlug('paysages')
            ->setPosition(-1);

        $errors = $this->validator->validate($photoType);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('position', $errors[0]->getPropertyPath());
    }

    /**
     * Test the ID via Reflection for complete coverage.
     */
    public function testGetId(): void
    {
        $photoType = new PhotoType();

        $reflection = new \ReflectionClass($photoType);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($photoType, 32);

        $this->assertSame(32, $photoType->getId());
    }
}
