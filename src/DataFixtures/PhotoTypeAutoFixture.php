<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\PhotoType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PhotoTypeAutoFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $photoType0 = new PhotoType();
        $photoType0->setName('Sky');
        $photoType0->setPosition(1);
        $photoType0->setCreatedAt(new \DateTime('2026-05-20 22:46:52'));
        $photoType0->setUpdatedAt(new \DateTime('2026-05-20 22:46:52'));
        $photoType0->setLabel('sky');
        $manager->persist($photoType0);
        $this->addReference('photoType_1', $photoType0);

        $photoType1 = new PhotoType();
        $photoType1->setName('Nature');
        $photoType1->setPosition(2);
        $photoType1->setCreatedAt(new \DateTime('2026-05-20 22:46:52'));
        $photoType1->setUpdatedAt(new \DateTime('2026-05-20 22:46:52'));
        $photoType1->setLabel('nature');
        $manager->persist($photoType1);
        $this->addReference('photoType_2', $photoType1);

        $photoType2 = new PhotoType();
        $photoType2->setName('Night');
        $photoType2->setPosition(3);
        $photoType2->setCreatedAt(new \DateTime('2026-05-20 22:46:52'));
        $photoType2->setUpdatedAt(new \DateTime('2026-05-20 22:46:52'));
        $photoType2->setLabel('night');
        $manager->persist($photoType2);
        $this->addReference('photoType_3', $photoType2);

        $photoType3 = new PhotoType();
        $photoType3->setName('City');
        $photoType3->setPosition(4);
        $photoType3->setCreatedAt(new \DateTime('2026-05-20 22:46:52'));
        $photoType3->setUpdatedAt(new \DateTime('2026-05-20 22:46:52'));
        $photoType3->setLabel('city');
        $manager->persist($photoType3);
        $this->addReference('photoType_4', $photoType3);

        $photoType4 = new PhotoType();
        $photoType4->setName('Ici et ailleurs');
        $photoType4->setPosition(5);
        $photoType4->setCreatedAt(new \DateTime('2026-05-20 22:46:52'));
        $photoType4->setUpdatedAt(new \DateTime('2026-05-20 22:46:52'));
        $photoType4->setLabel('ici-ailleurs');
        $manager->persist($photoType4);
        $this->addReference('photoType_5', $photoType4);

        $manager->flush();
    }
}
