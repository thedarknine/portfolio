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
use App\Entity\ProjectTag;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProjectTagValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    /**
     * Nominal case: a complete and valid tag passes without issues.
     */
    public function testValidProjectTagHasNoViolation(): void
    {
        $tag = (new ProjectTag())
            ->setName('Backend')
            ->setSlug('backend')
            ->setPosition(0);

        $errors = $this->validator->validate($tag);
        $this->assertCount(0, $errors);

        $this->assertSame('Backend', $tag->getName());
        $this->assertSame('backend', $tag->getSlug());
    }

    /**
     * Test that a blank name triggers a violation.
     */
    public function testBlankNameTriggersViolation(): void
    {
        $tag = (new ProjectTag())
            ->setName('')
            ->setSlug('backend')
            ->setPosition(0);

        $errors = $this->validator->validate($tag);

        $this->assertGreaterThan(0, count($errors));

        $paths = array_map(fn ($e) => $e->getPropertyPath(), iterator_to_array($errors));
        $this->assertContains('name', $paths);
    }

    /**
     * Test that exceeding the maximum size on the name triggers a violation.
     *
     * NB: nécessite d'ajouter #[Assert\Length(max: 120)] dans NameableTrait,
     * en cohérence avec la colonne #[ORM\Column(length: 120)].
     */
    public function testNameTooLongTriggersViolation(): void
    {
        $tag = (new ProjectTag())
            ->setName(str_repeat('X', 121))
            ->setSlug('backend')
            ->setPosition(0);

        $errors = $this->validator->validate($tag);

        $this->assertGreaterThan(0, count($errors));

        $paths = array_map(fn ($e) => $e->getPropertyPath(), iterator_to_array($errors));
        $this->assertContains('name', $paths);
    }

    /**
     * Test that a missing position triggers a violation (SortableTrait).
     */
    public function testMissingPositionTriggersViolation(): void
    {
        $tag = (new ProjectTag())
            ->setName('Backend')
            ->setSlug('backend');

        $errors = $this->validator->validate($tag);

        $paths = array_map(fn ($e) => $e->getPropertyPath(), iterator_to_array($errors));
        $this->assertContains('position', $paths);
    }

    /**
     * Test that a negative position triggers a violation (SortableTrait).
     */
    public function testNegativePositionTriggersViolation(): void
    {
        $tag = (new ProjectTag())
            ->setName('Backend')
            ->setSlug('backend')
            ->setPosition(-1);

        $errors = $this->validator->validate($tag);

        $paths = array_map(fn ($e) => $e->getPropertyPath(), iterator_to_array($errors));
        $this->assertContains('position', $paths);
    }

    /**
     * Test the default value for PublishableTrait.
     */
    public function testIsNotPublishedByDefault(): void
    {
        $tag = new ProjectTag();

        $this->assertFalse($tag->isPublished());
    }

    public function testCanBePublished(): void
    {
        $tag = (new ProjectTag())->setPublished(true);

        $this->assertTrue($tag->isPublished());
    }

    /**
     * Test getting the ID via Reflection.
     */
    public function testGetId(): void
    {
        $tag = new ProjectTag();

        $reflection = new \ReflectionClass($tag);
        $property   = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($tag, 42);

        $this->assertSame(42, $tag->getId());
    }

    public function testGetProjectsReturnsEmptyCollection(): void
    {
        $tag = new ProjectTag();

        self::assertCount(0, $tag->getProjects());
    }

    public function testAddProjectShouldAddProjectToTag(): void
    {
        $tag     = new ProjectTag();
        $project = new Project();

        $tag->addProject($project);

        self::assertTrue($tag->getProjects()->contains($project));
        self::assertTrue($project->getTags()->contains($tag));
    }

    public function testAddProjectDoesNotDuplicate(): void
    {
        $tag     = new ProjectTag();
        $project = new Project();

        $tag->addProject($project);
        $tag->addProject($project);

        self::assertCount(1, $tag->getProjects());
    }

    public function testRemoveProjectShouldRemoveProjectFromTag(): void
    {
        $tag     = new ProjectTag();
        $project = new Project();

        $tag->addProject($project);
        $tag->removeProject($project);

        self::assertFalse($tag->getProjects()->contains($project));
        self::assertFalse($project->getTags()->contains($tag));
    }

    public function testToStringReturnsName(): void
    {
        $tag = (new ProjectTag())->setName('Frontend');

        $this->assertSame('Frontend', (string) $tag);
    }

    public function testToStringReturnsEmptyStringWhenNameIsNull(): void
    {
        $tag = new ProjectTag();

        $this->assertSame('', (string) $tag);
    }
}
