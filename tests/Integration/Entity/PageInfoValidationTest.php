<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
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
        $enumCases       = PageCategory::cases();
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
        $enumCases       = PageCategory::cases();
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
        $property   = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($page, 88);

        $this->assertSame(88, $page->getId());
    }

    public function testPageCannotBeItsOwnParent(): void
    {
        $page = $this->createValidPage();

        $page->setParent($page);

        $errors = $this->validator->validate($page);

        $this->assertCount(1, $errors);

        $error = $errors[0];

        $this->assertSame('parent', $error->getPropertyPath());
        $this->assertSame(
            'Une page ne peut pas être son propre parent.',
            $error->getMessage(),
        );
    }

    public function testSubPageCannotHaveChildren(): void
    {
        $root       = $this->createValidPage();
        $child      = $this->createValidPage();
        $grandChild = $this->createValidPage();

        $root->addChild($child);
        $child->addChild($grandChild);

        $errors = $this->validator->validate($child);

        $this->assertCount(1, $errors);

        $error = $errors[0];

        $this->assertSame(
            'Une sous-page ne peut pas avoir elle-même des sous-pages (profondeur maximale : 1).',
            $error->getMessage(),
        );
    }

    public function testSubPageCannotBeSelectedAsParent(): void
    {
        $root  = $this->createValidPage();
        $child = $this->createValidPage();
        $page  = $this->createValidPage();

        $root->addChild($child);

        $page->setParent($child);

        $errors = $this->validator->validate($page);

        $this->assertCount(1, $errors);

        $error = $errors[0];

        $this->assertSame(
            'Impossible de choisir une sous-page comme parent (profondeur maximale : 1).',
            $error->getMessage(),
        );
    }

    public function testRootPageMayHaveChildren(): void
    {
        $root  = $this->createValidPage();
        $child = $this->createValidPage();

        $root->addChild($child);

        $errors = $this->validator->validate($root);

        $this->assertCount(0, $errors);
    }

    public function testSubPageWithoutChildrenIsValid(): void
    {
        $root  = $this->createValidPage();
        $child = $this->createValidPage();

        $child->setParent($root);

        $errors = $this->validator->validate($child);

        $this->assertCount(0, $errors);
    }

    private function createValidPage(): PageInfo
    {
        return (new PageInfo())
            ->setTitle('Accueil')
            ->setSlug(uniqid('page-', true))
            ->setTechnicalName(uniqid('page-', true))
            ->setTagline('Tagline')
            ->setSubtitle('Subtitle')
            ->setQuote('Quote')
            ->setCategory(PageCategory::cases()[0])
            ->setPosition(1);
    }
}
