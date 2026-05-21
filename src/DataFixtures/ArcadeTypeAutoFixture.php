<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\ArcadeType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArcadeTypeAutoFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $arcadeType0 = new ArcadeType();
        $arcadeType0->setName('This is it!');
        $arcadeType0->setPosition(1);
        $arcadeType0->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType0->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType0->setLabel('final');
        $manager->persist($arcadeType0);
        $this->addReference('arcadeType_1', $arcadeType0);

        $arcadeType1 = new ArcadeType();
        $arcadeType1->setName('Les plans');
        $arcadeType1->setPosition(2);
        $arcadeType1->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType1->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType1->setLabel('at-the-beginning');
        $manager->persist($arcadeType1);
        $this->addReference('arcadeType_2', $arcadeType1);

        $arcadeType2 = new ArcadeType();
        $arcadeType2->setName('Side Panels');
        $arcadeType2->setPosition(3);
        $arcadeType2->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType2->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType2->setLabel('side-panels');
        $manager->persist($arcadeType2);
        $this->addReference('arcadeType_3', $arcadeType2);

        $arcadeType3 = new ArcadeType();
        $arcadeType3->setName('Control Panel');
        $arcadeType3->setPosition(4);
        $arcadeType3->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType3->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType3->setLabel('control-panel');
        $manager->persist($arcadeType3);
        $this->addReference('arcadeType_4', $arcadeType3);

        $arcadeType4 = new ArcadeType();
        $arcadeType4->setName('Upper Part');
        $arcadeType4->setPosition(5);
        $arcadeType4->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType4->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType4->setLabel('upper-part');
        $manager->persist($arcadeType4);
        $this->addReference('arcadeType_5', $arcadeType4);

        $arcadeType5 = new ArcadeType();
        $arcadeType5->setName('Back Side');
        $arcadeType5->setPosition(6);
        $arcadeType5->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType5->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType5->setLabel('back-side');
        $manager->persist($arcadeType5);
        $this->addReference('arcadeType_6', $arcadeType5);

        $arcadeType6 = new ArcadeType();
        $arcadeType6->setName('Assemblage');
        $arcadeType6->setPosition(7);
        $arcadeType6->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType6->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType6->setLabel('assembling');
        $manager->persist($arcadeType6);
        $this->addReference('arcadeType_7', $arcadeType6);

        $arcadeType7 = new ArcadeType();
        $arcadeType7->setName('Design');
        $arcadeType7->setPosition(8);
        $arcadeType7->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType7->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType7->setLabel('design');
        $manager->persist($arcadeType7);
        $this->addReference('arcadeType_8', $arcadeType7);

        $arcadeType8 = new ArcadeType();
        $arcadeType8->setName('Recalbox');
        $arcadeType8->setPosition(9);
        $arcadeType8->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType8->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType8->setLabel('recalbox');
        $manager->persist($arcadeType8);
        $this->addReference('arcadeType_9', $arcadeType8);

        $arcadeType9 = new ArcadeType();
        $arcadeType9->setName('Matériel');
        $arcadeType9->setPosition(10);
        $arcadeType9->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType9->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType9->setLabel('materiel');
        $manager->persist($arcadeType9);
        $this->addReference('arcadeType_10', $arcadeType9);

        $arcadeType10 = new ArcadeType();
        $arcadeType10->setName('Composants');
        $arcadeType10->setPosition(11);
        $arcadeType10->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType10->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $arcadeType10->setLabel('composants');
        $manager->persist($arcadeType10);
        $this->addReference('arcadeType_11', $arcadeType10);

        $manager->flush();
    }
}
