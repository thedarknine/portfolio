<?php

namespace App\DataFixtures;

use App\Entity\ArcadeType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ArcadeTypeAutoFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $arcadeType0 = new ArcadeType();
        $arcadeType0->setCreatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType0->setUpdatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType0->setName('This is it!');
        $arcadeType0->setSlug('final');
        $arcadeType0->setPosition(1);
        $manager->persist($arcadeType0);
        $this->addReference('arcadeType_1', $arcadeType0);

        $arcadeType1 = new ArcadeType();
        $arcadeType1->setCreatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType1->setUpdatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType1->setName('Les plans');
        $arcadeType1->setSlug('at-the-beginning');
        $arcadeType1->setPosition(2);
        $manager->persist($arcadeType1);
        $this->addReference('arcadeType_2', $arcadeType1);

        $arcadeType2 = new ArcadeType();
        $arcadeType2->setCreatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType2->setUpdatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType2->setName('Side Panels');
        $arcadeType2->setSlug('side-panels');
        $arcadeType2->setPosition(3);
        $manager->persist($arcadeType2);
        $this->addReference('arcadeType_3', $arcadeType2);

        $arcadeType3 = new ArcadeType();
        $arcadeType3->setCreatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType3->setUpdatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType3->setName('Control Panel');
        $arcadeType3->setSlug('control-panel');
        $arcadeType3->setPosition(4);
        $manager->persist($arcadeType3);
        $this->addReference('arcadeType_4', $arcadeType3);

        $arcadeType4 = new ArcadeType();
        $arcadeType4->setCreatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType4->setUpdatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType4->setName('Upper Part');
        $arcadeType4->setSlug('upper-part');
        $arcadeType4->setPosition(5);
        $manager->persist($arcadeType4);
        $this->addReference('arcadeType_5', $arcadeType4);

        $arcadeType5 = new ArcadeType();
        $arcadeType5->setCreatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType5->setUpdatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType5->setName('Back Side');
        $arcadeType5->setSlug('back-side');
        $arcadeType5->setPosition(6);
        $manager->persist($arcadeType5);
        $this->addReference('arcadeType_6', $arcadeType5);

        $arcadeType6 = new ArcadeType();
        $arcadeType6->setCreatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType6->setUpdatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType6->setName('Assemblage');
        $arcadeType6->setSlug('assembling');
        $arcadeType6->setPosition(7);
        $manager->persist($arcadeType6);
        $this->addReference('arcadeType_7', $arcadeType6);

        $arcadeType7 = new ArcadeType();
        $arcadeType7->setCreatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType7->setUpdatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType7->setName('Design');
        $arcadeType7->setSlug('design');
        $arcadeType7->setPosition(8);
        $manager->persist($arcadeType7);
        $this->addReference('arcadeType_8', $arcadeType7);

        $arcadeType8 = new ArcadeType();
        $arcadeType8->setCreatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType8->setUpdatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType8->setName('Recalbox');
        $arcadeType8->setSlug('recalbox');
        $arcadeType8->setPosition(9);
        $manager->persist($arcadeType8);
        $this->addReference('arcadeType_9', $arcadeType8);

        $arcadeType9 = new ArcadeType();
        $arcadeType9->setCreatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType9->setUpdatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType9->setName('Matériel');
        $arcadeType9->setSlug('materiel');
        $arcadeType9->setPosition(10);
        $manager->persist($arcadeType9);
        $this->addReference('arcadeType_10', $arcadeType9);

        $arcadeType10 = new ArcadeType();
        $arcadeType10->setCreatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType10->setUpdatedAt(new \DateTime('2026-05-27 12:54:01'));
        $arcadeType10->setName('Composants');
        $arcadeType10->setSlug('composants');
        $arcadeType10->setPosition(11);
        $manager->persist($arcadeType10);
        $this->addReference('arcadeType_11', $arcadeType10);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['testing_unit'];
    }
}
