<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Entity;

use App\Entity\Experience;
use App\Entity\Skill;
use App\Entity\SkillType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SkillValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    /**
     * Cas nominal : Une compétence valide passe sans encombre.
     */
    public function testValidSkillHasNoViolation(): void
    {
        $skill = (new Skill())
            ->setName('Symfony')
            ->setSlug('symfony')
            ->setStartYear(2011)
            ->setEndYear(2021)
            ->setLevel(90)
            ->setPosition(1)
            ->setDisplay(true)
            ->setSkillType(
                (new SkillType())
                    ->setName('Programming')
                    ->setSlug('programming')
                    ->setPosition(1)
            );

        $errors = $this->validator->validate($skill);
        $this->assertCount(0, $errors);

        $this->assertSame(2011, $skill->getStartYear());
        $this->assertSame(2021, $skill->getEndYear());
        $this->assertSame(90, $skill->getLevel());
        $this->assertTrue($skill->isDisplay());
        $this->assertSame(1, $skill->getPosition());
    }

    /**
     * Teste la méthode getDuration() pour une compétence terminée.
     */
    public function testGetDurationForPastSkill(): void
    {
        $skill = (new Skill())->setStartYear(2015)->setEndYear(2018);
        $this->assertSame('3 ans', $skill->getDuration());

        $skillShort = (new Skill())->setStartYear(2015)->setEndYear(2016);
        $this->assertSame('1 an', $skillShort->getDuration());
    }

    /**
     * Teste la méthode getDuration() pour une compétence toujours active (endYear === null).
     */
    public function testGetDurationForActiveSkill(): void
    {
        $currentYear = intval((new \DateTime())->format('Y'));

        $skill = (new Skill())->setStartYear($currentYear - 4);
        $this->assertSame('4 ans', $skill->getDuration());
    }

    /**
     * Teste le Callback de sécurité sur la cohérence des dates.
     */
    public function testInvalidEndYearTriggerCallbackViolation(): void
    {
        $skill = (new Skill())
            ->setName('PHP')
            ->setStartYear(2026)
            ->setEndYear(2022)
            ->setLevel(85)
            ->setSkillType(new SkillType());

        $errors = $this->validator->validate($skill);
        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('endYear', $errors[0]->getPropertyPath());
    }

    /**
     * Teste la gestion de la collection d'expériences (ManyToMany).
     */
    public function testExperienceCollectionManagement(): void
    {
        $skill = new Skill();
        $experience = new Experience();

        $this->assertCount(0, $skill->getExperiences());

        $skill->addExperience($experience);
        $this->assertCount(1, $skill->getExperiences());

        $skill->removeExperience($experience);
        $this->assertCount(0, $skill->getExperiences());
    }

    /**
     * Test de l'ID via Reflection.
     */
    public function testGetId(): void
    {
        $skill = new Skill();

        $reflection = new \ReflectionClass($skill);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($skill, 7);

        $this->assertSame(7, $skill->getId());
    }
}
