<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\CreationType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CreationTypeAutoFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $creationType0 = new CreationType();
        $creationType0->setName('Argile Mat');
        $creationType0->setPosition(1);
        $creationType0->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $creationType0->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $creationType0->setLabel('argile-mat');
        $manager->persist($creationType0);
        $this->addReference('creationType_1', $creationType0);

        $creationType1 = new CreationType();
        $creationType1->setName('Argile Ciré');
        $creationType1->setPosition(2);
        $creationType1->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $creationType1->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $creationType1->setLabel('argile-cire');
        $manager->persist($creationType1);
        $this->addReference('creationType_2', $creationType1);

        $creationType2 = new CreationType();
        $creationType2->setName('Argile Peint');
        $creationType2->setPosition(3);
        $creationType2->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $creationType2->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $creationType2->setLabel('argile-peint');
        $manager->persist($creationType2);
        $this->addReference('creationType_3', $creationType2);

        $creationType3 = new CreationType();
        $creationType3->setName('Argile Vernis');
        $creationType3->setPosition(4);
        $creationType3->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $creationType3->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $creationType3->setLabel('argile-vernis');
        $manager->persist($creationType3);
        $this->addReference('creationType_4', $creationType3);

        $creationType4 = new CreationType();
        $creationType4->setName('Au tour');
        $creationType4->setPosition(5);
        $creationType4->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $creationType4->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $creationType4->setLabel('argile-tour');
        $manager->persist($creationType4);
        $this->addReference('creationType_5', $creationType4);

        $manager->flush();
    }
}
