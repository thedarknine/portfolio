<?php

namespace App\DataFixtures;

use App\Entity\PhotoType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PhotoTypeAutoFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $photoType0 = new PhotoType();
        $photoType0->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $photoType0->setUpdatedAt(new \DateTime('2026-05-24 22:42:07'));
        $photoType0->setName('Sky');
        $photoType0->setSlug('sky');
        $photoType0->setPosition(1);
        $manager->persist($photoType0);
        $this->addReference('photoType_6', $photoType0);

        $photoType1 = new PhotoType();
        $photoType1->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $photoType1->setUpdatedAt(new \DateTime('2026-05-24 22:42:07'));
        $photoType1->setName('Nature');
        $photoType1->setSlug('nature');
        $photoType1->setPosition(2);
        $manager->persist($photoType1);
        $this->addReference('photoType_7', $photoType1);

        $photoType2 = new PhotoType();
        $photoType2->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $photoType2->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $photoType2->setName('Night');
        $photoType2->setSlug('night');
        $photoType2->setPosition(3);
        $manager->persist($photoType2);
        $this->addReference('photoType_8', $photoType2);

        $photoType3 = new PhotoType();
        $photoType3->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $photoType3->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $photoType3->setName('City');
        $photoType3->setSlug('city');
        $photoType3->setPosition(4);
        $manager->persist($photoType3);
        $this->addReference('photoType_9', $photoType3);

        $photoType4 = new PhotoType();
        $photoType4->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $photoType4->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $photoType4->setName('Ici et ailleurs');
        $photoType4->setSlug('ici-ailleurs');
        $photoType4->setPosition(5);
        $manager->persist($photoType4);
        $this->addReference('photoType_10', $photoType4);

        $manager->flush();
    }
}
