<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Entity;

use App\Entity\Company;
use App\Entity\Experience;
use App\Entity\ExperienceLink;
use App\Enum\LinkType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ExperienceLinkValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    /**
     * Nominal case: a valid experience link passes without violations.
     */
    public function testValidExperienceLinkHasNoViolation(): void
    {
        $enumCases   = LinkType::cases();
        $defaultType = !empty($enumCases) ? $enumCases[0] : null;

        $link = (new ExperienceLink())
            ->setTitle('Dépôt GitHub')
            ->setSlug('depot-github')
            ->setUrl('https://github.com/carolinenoyer')
            ->setExperience($this->createValidMockExperience())
            ->setType($defaultType);

        $errors = $this->validator->validate($link);
        $this->assertCount(0, $errors);

        $this->assertSame('Dépôt GitHub', $link->getTitle());
        $this->assertSame('depot-github', $link->getSlug());
        $this->assertSame('https://github.com/carolinenoyer', $link->getUrl());
        $this->assertSame($defaultType, $link->getType());
    }

    /**
     * Invalid URL format trigger violation.
     */
    public function testInvalidUrlFormatTriggerViolation(): void
    {
        $link = (new ExperienceLink())
            ->setTitle('Lien cassé')
            ->setUrl('pas-une-url-valide')
            ->setExperience($this->createValidMockExperience());

        $errors = $this->validator->validate($link);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('url', $errors[0]->getPropertyPath());
    }

    /**
     * Test ID via Reflection.
     */
    public function testGetId(): void
    {
        $link = new ExperienceLink();

        $reflection = new \ReflectionClass($link);
        $property   = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($link, 742);

        $this->assertSame(742, $link->getId());
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
}
