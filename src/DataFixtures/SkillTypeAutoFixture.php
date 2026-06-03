<?php

namespace App\DataFixtures;

use App\Entity\SkillType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class SkillTypeAutoFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $skillType0 = new SkillType();
        $skillType0->setDescription(null);
        $skillType0->setDeleted(true);
        $skillType0->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skillType0->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skillType0->setName('Méthodologie et Agilité');
        $skillType0->setSlug('methodo');
        $skillType0->setLogo('skills-methodologie.png');
        $skillType0->setPosition(1);
        $manager->persist($skillType0);
        $this->addReference('skillType_10', $skillType0);

        $skillType1 = new SkillType();
        $skillType1->setDescription(null);
        $skillType1->setDeleted(true);
        $skillType1->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skillType1->setUpdatedAt(new \DateTime('2026-05-26 07:39:44'));
        $skillType1->setName('Design');
        $skillType1->setSlug('design-old');
        $skillType1->setLogo('skills-design.png');
        $skillType1->setPosition(3);
        $manager->persist($skillType1);
        $this->addReference('skillType_11', $skillType1);

        $skillType2 = new SkillType();
        $skillType2->setDescription(null);
        $skillType2->setDeleted(true);
        $skillType2->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skillType2->setUpdatedAt(new \DateTime('2026-05-24 18:29:36'));
        $skillType2->setName('Back-End');
        $skillType2->setSlug('back-end');
        $skillType2->setLogo('skills-backend.png');
        $skillType2->setPosition(3);
        $manager->persist($skillType2);
        $this->addReference('skillType_12', $skillType2);

        $skillType3 = new SkillType();
        $skillType3->setDescription(null);
        $skillType3->setDeleted(true);
        $skillType3->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skillType3->setUpdatedAt(new \DateTime('2026-05-24 18:29:36'));
        $skillType3->setName('Front-End');
        $skillType3->setSlug('front-end');
        $skillType3->setLogo('skills-frontend.png');
        $skillType3->setPosition(3);
        $manager->persist($skillType3);
        $this->addReference('skillType_13', $skillType3);

        $skillType4 = new SkillType();
        $skillType4->setDescription(null);
        $skillType4->setDeleted(true);
        $skillType4->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skillType4->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skillType4->setName('Systèmes et Administration');
        $skillType4->setSlug('sysadmin');
        $skillType4->setLogo('skills-sysadmin.png');
        $skillType4->setPosition(5);
        $manager->persist($skillType4);
        $this->addReference('skillType_14', $skillType4);

        $skillType5 = new SkillType();
        $skillType5->setDescription('Mon cœur de métier historique. J\'aime concevoir des architectures back-end propres, scalables et performantes.');
        $skillType5->setDeleted(false);
        $skillType5->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skillType5->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skillType5->setName('Architecture & Développement');
        $skillType5->setSlug('development');
        $skillType5->setLogo('skills-development.png');
        $skillType5->setPosition(1);
        $manager->persist($skillType5);
        $this->addReference('skillType_15', $skillType5);

        $skillType6 = new SkillType();
        $skillType6->setDescription('Faire le pont entre la vision business, le design et les contraintes techniques de l\'équipe de R&D.');
        $skillType6->setDeleted(false);
        $skillType6->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skillType6->setUpdatedAt(new \DateTime('2026-05-24 18:29:37'));
        $skillType6->setName('Product Ownership');
        $skillType6->setSlug('product');
        $skillType6->setLogo('skills-product.png');
        $skillType6->setPosition(3);
        $manager->persist($skillType6);
        $this->addReference('skillType_16', $skillType6);

        $skillType7 = new SkillType();
        $skillType7->setDescription('Parce qu\'un bon code ou une bonne feature ne valent rien sans une expérience utilisateur fluide et intuitive.');
        $skillType7->setDeleted(false);
        $skillType7->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skillType7->setUpdatedAt(new \DateTime('2026-05-24 18:29:36'));
        $skillType7->setName('Design & UX/UI');
        $skillType7->setSlug('design');
        $skillType7->setLogo('skills-design.png');
        $skillType7->setPosition(4);
        $manager->persist($skillType7);
        $this->addReference('skillType_17', $skillType7);

        $skillType8 = new SkillType();
        $skillType8->setDescription('Les indispensables qui fluidifient mon workflow et mes collaborations en équipe.');
        $skillType8->setDeleted(false);
        $skillType8->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $skillType8->setUpdatedAt(new \DateTime('2026-05-24 18:29:37'));
        $skillType8->setName('Méthodologies & Outils du quotidien');
        $skillType8->setSlug('methodologies');
        $skillType8->setLogo('skills-methodologies.png');
        $skillType8->setPosition(2);
        $manager->persist($skillType8);
        $this->addReference('skillType_18', $skillType8);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['portfolio'];
    }
}
