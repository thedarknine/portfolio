<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Entity;

use App\Entity\PageInfo;
use App\Enum\PageCategory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PageInfoValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    /**
     * Nominal case: A complete and valid page configuration passes without issues.
     */
    public function testValidPageInfoHasNoViolation(): void
    {
        $enumCases = PageCategory::cases();
        $defaultCategory = !empty($enumCases) ? $enumCases[0] : null;

        $page = (new PageInfo())
            ->setTitle('Expérience')
            ->setSlug('experience')
            ->setTechnicalName('experience')
            ->setTagline('Construire')
            ->setSubtitle('Du code au produit : mon parcours en mouvement')
            ->setQuote('J’aime transformer des idées en réalisations concrètes.')
            ->setInHeader(true)
            ->setPosition(1)
            ->setCategory($defaultCategory);

        $errors = $this->validator->validate($page);
        $this->assertCount(0, $errors);

        $this->assertSame('experience', $page->getTechnicalName());
        $this->assertSame('Construire', $page->getTagline());
        $this->assertSame('Du code au produit : mon parcours en mouvement', $page->getSubtitle());
        $this->assertSame('J’aime transformer des idées en réalisations concrètes.', $page->getQuote());
        $this->assertTrue($page->isInHeader());
        $this->assertSame($defaultCategory, $page->getCategory());
    }

    /**
     * Test blank fields trigger violations.
     */
    public function testBlankFieldsTriggerViolations(): void
    {
        $page = (new PageInfo())
            ->setTitle('Page Vide')
            ->setSlug('page-vide')
            ->setTechnicalName('')
            ->setTagline('Tagline')
            ->setSubtitle('')
            ->setQuote('Une citation')
            ->setInHeader(false)
            ->setPosition(2);

        $errors = $this->validator->validate($page);

        $this->assertGreaterThan(0, count($errors));

        $paths = array_map(fn ($e) => $e->getPropertyPath(), iterator_to_array($errors));
        $this->assertContains('technicalName', $paths);
        $this->assertContains('subtitle', $paths);
    }

    /**
     * Test negative position trigger violation.
     */
    public function testNegativePositionTriggerViolation(): void
    {
        $enumCases = PageCategory::cases();
        $defaultCategory = !empty($enumCases) ? $enumCases[0] : null;

        $page = (new PageInfo())
            ->setTitle('Page Mal Rangée')
            ->setSlug('page-mal-rangee')
            ->setTechnicalName('mal-rangee')
            ->setTagline('Oups')
            ->setSubtitle('Un sous-titre')
            ->setQuote('Une citation')
            ->setInHeader(true)
            ->setPosition(-1)
            ->setCategory($defaultCategory);

        $errors = $this->validator->validate($page);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('position', $errors[0]->getPropertyPath());
    }

    /**
     * Test get ID via Reflection.
     */
    public function testGetId(): void
    {
        $page = new PageInfo();

        $reflection = new \ReflectionClass($page);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($page, 88);

        $this->assertSame(88, $page->getId());
    }
}
