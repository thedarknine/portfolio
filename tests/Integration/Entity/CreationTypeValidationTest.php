<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Entity;

use App\Entity\CreationType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreationTypeValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    /**
     * Nominal case: A valid creation type passes without issues.
     */
    public function testValidCreationTypeHasNoViolation(): void
    {
        $creationType = (new CreationType())
            ->setName('Céramique')
            ->setSlug('ceramique')
            ->setPosition(0);

        $errors = $this->validator->validate($creationType);
        $this->assertCount(0, $errors);

        $this->assertSame('Céramique', $creationType->getName());
        $this->assertSame('ceramique', $creationType->getSlug());
        $this->assertSame(0, $creationType->getPosition());
    }

    /**
     * Test that the name (and by extension the slug) cannot be empty.
     */
    public function testBlankNameTriggerViolation(): void
    {
        $creationType = (new CreationType())
            ->setName('')
            ->setPosition(1);

        $errors = $this->validator->validate($creationType);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('name', $errors[0]->getPropertyPath());
    }

    /**
     * Test that the position cannot be negative.
     */
    public function testNegativePositionTriggerViolation(): void
    {
        $creationType = (new CreationType())
            ->setName('Sculpture')
            ->setSlug('sculpture')
            ->setPosition(-1);

        $errors = $this->validator->validate($creationType);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('position', $errors[0]->getPropertyPath());
    }

    /**
     * Test the getId method via Reflection for complete coverage.
     */
    public function testGetId(): void
    {
        $creationType = new CreationType();

        $reflection = new \ReflectionClass($creationType);
        $property   = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($creationType, 12);

        $this->assertSame(12, $creationType->getId());
    }
}
