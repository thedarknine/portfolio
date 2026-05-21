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
        $skillType0->setDeleted(true);
        $manager->persist($skillType0);
        $this->addReference('skillType_1', $skillType0);

        $skillType1 = new SkillType();
        $skillType1->setName('Design');
        $skillType1->setPosition(2);
        $skillType1->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType1->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType1->setLabel('design');
        $skillType1->setLogo('skills-design.png');
        $skillType1->setDeleted(true);
        $manager->persist($skillType1);
        $this->addReference('skillType_2', $skillType1);

        $skillType2 = new SkillType();
        $skillType2->setName('Back-End');
        $skillType2->setPosition(3);
        $skillType2->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType2->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType2->setLabel('back-end');
        $skillType2->setLogo('skills-backend.png');
        $skillType2->setDeleted(true);
        $manager->persist($skillType2);
        $this->addReference('skillType_3', $skillType2);

        $skillType3 = new SkillType();
        $skillType3->setName('Front-End');
        $skillType3->setPosition(4);
        $skillType3->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType3->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType3->setLabel('front-end');
        $skillType3->setLogo('skills-frontend.png');
        $skillType3->setDeleted(true);
        $manager->persist($skillType3);
        $this->addReference('skillType_4', $skillType3);

        $skillType4 = new SkillType();
        $skillType4->setName('Systèmes et Administration');
        $skillType4->setPosition(5);
        $skillType4->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType4->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $skillType4->setLabel('sysadmin');
        $skillType4->setLogo('skills-sysadmin.png');
        $skillType4->setDeleted(true);
        $manager->persist($skillType4);
        $this->addReference('skillType_5', $skillType4);

        $skillType5 = new SkillType();
        $skillType5->setName('Architecture & Développement');
        $skillType5->setPosition(1);
        $skillType5->setLabel('development');
        $skillType5->setLogo('skills-development.png');
        $skillType5->setDescription('Mon cœur de métier historique. J\'aime concevoir des architectures back-end propres, scalables et performantes.');
        $skillType5->setDeleted(false);
        $manager->persist($skillType5);
        $this->addReference('skillType_6', $skillType5);

        $skillType6 = new SkillType();
        $skillType6->setName('Product Ownership');
        $skillType6->setPosition(2);
        $skillType6->setLabel('product');
        $skillType6->setLogo('skills-product.png');
        $skillType6->setDescription('Faire le pont entre la vision business, le design et les contraintes techniques de l\'équipe de R&D.');
        $skillType6->setDeleted(false);
        $manager->persist($skillType6);
        $this->addReference('skillType_7', $skillType6);

        $skillType7 = new SkillType();
        $skillType7->setName('Design & UX/UI');
        $skillType7->setPosition(3);
        $skillType7->setLabel('design');
        $skillType7->setLogo('skills-design.png');
        $skillType7->setDescription('Parce qu\'un bon code ou une bonne feature ne valent rien sans une expérience utilisateur fluide et intuitive.');
        $skillType7->setDeleted(false);
        $manager->persist($skillType7);
        $this->addReference('skillType_8', $skillType7);

        $skillType8 = new SkillType();
        $skillType8->setName('Méthodologies & Outils du quotidien');
        $skillType8->setPosition(4);
        $skillType8->setLabel('methodologies');
        $skillType8->setLogo('skills-methodologies.png');
        $skillType8->setDescription('Les indispensables qui fluidifient mon workflow et mes collaborations en équipe.');
        $skillType8->setDeleted(false);
        $manager->persist($skillType8);
        $this->addReference('skillType_9', $skillType8);

        $manager->flush();
    }
}
