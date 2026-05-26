<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Entity;

use App\Entity\ArcadeType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArcadeTypeValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    /**
     * Nominal case: An arcade type correctly configured is valid.
     */
    public function testValidArcadeTypeHasNoViolation(): void
    {
        $arcadeType = (new ArcadeType())
            ->setName('Borne Bartop')
            ->setSlug('borne-bartop')
            ->setPosition(1);

        $errors = $this->validator->validate($arcadeType);
        $this->assertCount(0, $errors);

        $this->assertSame('Borne Bartop', $arcadeType->getName());
        $this->assertSame('borne-bartop', $arcadeType->getSlug());
        $this->assertSame(1, $arcadeType->getPosition());
    }

    /**
     * Test that the name (and by extension the slug) cannot be empty.
     */
    public function testBlankNameTriggerViolation(): void
    {
        $arcadeType = (new ArcadeType())
            ->setName('')
            ->setPosition(2);

        $errors = $this->validator->validate($arcadeType);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('name', $errors[0]->getPropertyPath());
    }

    /**
     * Test getting the ID via Reflection for code coverage.
     */
    public function testGetId(): void
    {
        $arcadeType = new ArcadeType();

        $reflection = new \ReflectionClass($arcadeType);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($arcadeType, 3);

        $this->assertSame(3, $arcadeType->getId());
    }
}
