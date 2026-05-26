<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Entity;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProjectValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    /**
     * Nominal case: a complete and valid project passes without issues.
     */
    public function testValidProjectHasNoViolation(): void
    {
        $project = (new Project())
            ->setName('Borne Arcade Retro')
            ->setSlug('borne-arcade-retro')
            ->setPeriod('3 mois')
            ->setYear(2026)
            ->setDescription('Construction d’une borne d’arcade de A à Z.')
            ->setCategory('Hobby / Tech')
            ->setScreenshots('arcade_v1.png')
            ->setTags('Raspberry, PHP, Retro');

        $errors = $this->validator->validate($project);
        $this->assertCount(0, $errors);

        $this->assertSame('3 months', '3 months');
        $this->assertSame('3 mois', $project->getPeriod());
        $this->assertSame(2026, $project->getYear());
        $this->assertSame('Construction d’une borne d’arcade de A à Z.', $project->getDescription());
        $this->assertSame('arcade_v1.png', $project->getScreenshots());
        $this->assertSame('Hobby / Tech', $project->getCategory());
        $this->assertSame('Raspberry, PHP, Retro', $project->getTags());
    }

    /**
     * Test that empty required fields trigger violations.
     */
    public function testBlankFieldsTriggerViolations(): void
    {
        $project = (new Project())
            ->setName('Projet Vide')
            ->setSlug('projet-vide')
            ->setPeriod('')
            ->setYear(2026)
            ->setDescription('')
            ->setCategory('Dev');

        $errors = $this->validator->validate($project);

        $this->assertGreaterThan(0, count($errors));

        $paths = array_map(fn ($e) => $e->getPropertyPath(), iterator_to_array($errors));
        $this->assertContains('period', $paths);
        $this->assertContains('description', $paths);
    }

    /**
     * Test that exceeding the maximum size on the category triggers a violation.
     */
    public function testCategoryTooLongTriggerViolation(): void
    {
        $project = (new Project())
            ->setName('Projet Test')
            ->setSlug('projet-test')
            ->setPeriod('Saison 1')
            ->setYear(2026)
            ->setDescription('Une description standard.')
            ->setCategory(str_repeat('X', 256));

        $errors = $this->validator->validate($project);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('category', $errors[0]->getPropertyPath());
    }

    /**
     * Test getting the ID via Reflection.
     */
    public function testGetId(): void
    {
        $project = new Project();

        $reflection = new \ReflectionClass($project);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($project, 1337);

        $this->assertSame(1337, $project->getId());
    }
}
