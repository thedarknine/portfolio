<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\SkillType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SkillTypeAutoFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $skillType0 = new SkillType();
        $skillType0->setName('Méthodologie et Agilité');
        $skillType0->setPosition(1);
        $skillType0->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType0->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType0->setLabel('methodo');
        $skillType0->setLogo('skills-methodologie.png');
        $manager->persist($skillType0);
        $this->addReference('skillType_1', $skillType0);

        $skillType1 = new SkillType();
        $skillType1->setName('Design');
        $skillType1->setPosition(2);
        $skillType1->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType1->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType1->setLabel('design');
        $skillType1->setLogo('skills-design.png');
        $manager->persist($skillType1);
        $this->addReference('skillType_2', $skillType1);

        $skillType2 = new SkillType();
        $skillType2->setName('Back-End');
        $skillType2->setPosition(3);
        $skillType2->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType2->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType2->setLabel('back-end');
        $skillType2->setLogo('skills-backend.png');
        $manager->persist($skillType2);
        $this->addReference('skillType_3', $skillType2);

        $skillType3 = new SkillType();
        $skillType3->setName('Front-End');
        $skillType3->setPosition(4);
        $skillType3->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType3->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType3->setLabel('front-end');
        $skillType3->setLogo('skills-frontend.png');
        $manager->persist($skillType3);
        $this->addReference('skillType_4', $skillType3);

        $skillType4 = new SkillType();
        $skillType4->setName('Systèmes et Administration');
        $skillType4->setPosition(5);
        $skillType4->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType4->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType4->setLabel('sysadmin');
        $skillType4->setLogo('skills-sysadmin.png');
        $manager->persist($skillType4);
        $this->addReference('skillType_5', $skillType4);

        $manager->flush();
    }
}
