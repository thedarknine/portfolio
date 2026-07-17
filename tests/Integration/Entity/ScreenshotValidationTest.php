<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Entity;

use App\Entity\Project;
use App\Entity\Screenshot;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ScreenshotValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->validator = static::getContainer()->get(ValidatorInterface::class);
    }

    /**
     * Test get ID via Reflection.
     */
    public function testGetId(): void
    {
        $screenshot = new Screenshot();

        $reflection = new \ReflectionClass($screenshot);
        $property   = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($screenshot, 88);

        $this->assertSame(88, $screenshot->getId());
    }

    public function testToStringReturnsFilename(): void
    {
        $screenshot = (new Screenshot())
            ->setFilename('image.jpg');

        $this->assertSame('image.jpg', (string) $screenshot);
    }

    public function testToStringReturnsEmptyStringWhenFilenameIsNull(): void
    {
        $screenshot = new Screenshot();

        $this->assertSame('', (string) $screenshot);
    }

    public function testItIsValidWhenAllFieldsAreFilled(): void
    {
        $screenshot = (new Screenshot())
            ->setFilename('image.jpg')
            ->setDescription('Description de la capture')
            ->setPosition(1);

        $project = new Project();
        $screenshot->setProject($project);

        $violations = $this->validator->validate($screenshot);

        $this->assertCount(0, $violations);
    }

    public function testFilenameMustNotBeBlank(): void
    {
        $screenshot = (new Screenshot())
            ->setDescription('Description de la capture');

        $project = new Project();
        $screenshot->setProject($project);

        $violations = $this->validator->validate($screenshot);

        $this->assertGreaterThan(0, $violations->count());
        $this->assertSame('filename', $violations[0]->getPropertyPath());
        $this->assertSame('This value should not be blank.', $violations[0]->getMessage());
    }

    public function testDescriptionMustNotBeBlank(): void
    {
        $screenshot = (new Screenshot())
            ->setFilename('image.jpg');

        $project = new Project();
        $screenshot->setProject($project);

        $violations = $this->validator->validate($screenshot);

        $this->assertGreaterThan(0, $violations->count());
        $this->assertSame('description', $violations[0]->getPropertyPath());
        $this->assertSame('This value should not be blank.', $violations[0]->getMessage());
    }

    public function testPositionMustNotBeBlank(): void
    {
        $screenshot = (new Screenshot())
            ->setFilename('image.jpg')
            ->setDescription('Description de la capture');

        $project = new Project();
        $screenshot->setProject($project);

        $violations = $this->validator->validate($screenshot);

        $this->assertGreaterThan(0, $violations->count());
        $this->assertSame('position', $violations[0]->getPropertyPath());
        $this->assertSame('Position is required.', $violations[0]->getMessage());
    }
}
