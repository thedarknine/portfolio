<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Entity;

use App\Entity\ResourceLink;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ResourceLinkValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    /**
     * Nominal case: A resource passes validation without violations.
     */
    public function testValidResourceLinkHasNoViolation(): void
    {
        $resource = (new ResourceLink())
            ->setTitle('Documentation Symfony')
            ->setSlug('documentation-symfony')
            ->setUrl('https://symfony.com/doc/current/index.html')
            ->setInHero(true)
            ->setIcon('fab fa-symfony')
            ->setPosition(1);

        $errors = $this->validator->validate($resource);
        $this->assertCount(0, $errors);

        $this->assertSame('https://symfony.com/doc/current/index.html', $resource->getUrl());
        $this->assertTrue($resource->isInHero());
        $this->assertSame('fab fa-symfony', $resource->getIcon());
        $this->assertSame(1, $resource->getPosition());
    }

    /**
     * Invalid URL format test.
     */
    public function testInvalidUrlFormatTriggerViolation(): void
    {
        $resource = (new ResourceLink())
            ->setTitle('Lien cassé')
            ->setSlug('lien-casse')
            ->setUrl('not-a-valid-url')
            ->setInHero(false)
            ->setIcon('fab fa-link')
            ->setPosition(1);

        $errors = $this->validator->validate($resource);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('url', $errors[0]->getPropertyPath());
    }

    /**
     * Negative position validation test.
     */
    public function testNegativePositionTriggerViolation(): void
    {
        $resource = (new ResourceLink())
            ->setTitle('Lien mal rangé')
            ->setSlug('lien-mal-rangé')
            ->setUrl('https://carolinenoyer.fr')
            ->setInHero(false)
            ->setIcon('fab fa-link')
            ->setPosition(-10);

        $errors = $this->validator->validate($resource);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('position', $errors[0]->getPropertyPath());
    }

    /**
     * ID getter test via Reflection.
     */
    public function testGetId(): void
    {
        $resource = new ResourceLink();

        $reflection = new \ReflectionClass($resource);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($resource, 42);

        $this->assertSame(42, $resource->getId());
    }
}
