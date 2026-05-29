<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Entity;

use App\Entity\Skill;
use App\Entity\SkillType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SkillTypeValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    /**
     * Nominal case: A valid skill type passes without violations.
     */
    public function testValidSkillTypeHasNoViolation(): void
    {
        $skillType = (new SkillType())
            ->setName('Développement Back-End')
            ->setSlug('developpement-back-end')
            ->setDescription('Compétences liées aux architectures serveurs et API.')
            ->setDeleted(false)
            ->setPosition(1);

        $errors = $this->validator->validate($skillType);
        $this->assertCount(0, $errors);

        $this->assertSame('Développement Back-End', $skillType->getName());
        $this->assertSame('developpement-back-end', $skillType->getSlug());
        $this->assertSame('Compétences liées aux architectures serveurs et API.', $skillType->getDescription());
        $this->assertFalse($skillType->isDeleted());
        $this->assertSame(1, $skillType->getPosition());
    }

    /**
     * Test the complete management of the skills collection (add/remove).
     */
    public function testSkillsCollectionManagement(): void
    {
        $skillType = new SkillType();
        $skill     = new Skill();

        $this->assertCount(0, $skillType->getSkills());

        $skillType->addSkill($skill);
        $this->assertCount(1, $skillType->getSkills());
        $this->assertSame($skillType, $skill->getSkillType());

        $skillType->removeSkill($skill);
        $this->assertCount(0, $skillType->getSkills());
    }

    /**
     * Test the inherited validation from SortableTrait (negative position).
     */
    public function testNegativePositionTriggerViolation(): void
    {
        $skillType = (new SkillType())
            ->setName('Design UX/UI')
            ->setSlug('design-ux-ui')
            ->setPosition(-2);

        $errors = $this->validator->validate($skillType);

        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('position', $errors[0]->getPropertyPath());
    }

    /**
     * Test the ID via Reflection.
     */
    public function testGetId(): void
    {
        $skillType = new SkillType();

        $reflection = new \ReflectionClass($skillType);
        $property   = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($skillType, 404);

        $this->assertSame(404, $skillType->getId());
    }
}
