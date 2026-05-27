<?php

namespace App\DataFixtures;

use App\Entity\CreationType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CreationTypeAutoFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $creationType0 = new CreationType();
        $creationType0->setCreatedAt(new \DateTime('2026-05-27 12:54:01'));
        $creationType0->setUpdatedAt(new \DateTime('2026-05-27 12:54:01'));
        $creationType0->setName('Argile Mat');
        $creationType0->setSlug('argile-mat');
        $creationType0->setPosition(1);
        $manager->persist($creationType0);
        $this->addReference('creationType_1', $creationType0);

        $creationType1 = new CreationType();
        $creationType1->setCreatedAt(new \DateTime('2026-05-27 12:54:01'));
        $creationType1->setUpdatedAt(new \DateTime('2026-05-27 12:54:01'));
        $creationType1->setName('Argile Ciré');
        $creationType1->setSlug('argile-cire');
        $creationType1->setPosition(2);
        $manager->persist($creationType1);
        $this->addReference('creationType_2', $creationType1);

        $creationType2 = new CreationType();
        $creationType2->setCreatedAt(new \DateTime('2026-05-27 12:54:01'));
        $creationType2->setUpdatedAt(new \DateTime('2026-05-27 12:54:01'));
        $creationType2->setName('Argile Peint');
        $creationType2->setSlug('argile-peint');
        $creationType2->setPosition(4);
        $manager->persist($creationType2);
        $this->addReference('creationType_3', $creationType2);

        $creationType3 = new CreationType();
        $creationType3->setCreatedAt(new \DateTime('2026-05-27 12:54:01'));
        $creationType3->setUpdatedAt(new \DateTime('2026-05-27 12:54:01'));
        $creationType3->setName('Argile Vernis');
        $creationType3->setSlug('argile-vernis');
        $creationType3->setPosition(4);
        $manager->persist($creationType3);
        $this->addReference('creationType_4', $creationType3);

        $creationType4 = new CreationType();
        $creationType4->setCreatedAt(new \DateTime('2026-05-27 12:54:01'));
        $creationType4->setUpdatedAt(new \DateTime('2026-05-27 12:54:01'));
        $creationType4->setName('Au tour');
        $creationType4->setSlug('argile-tour');
        $creationType4->setPosition(5);
        $manager->persist($creationType4);
        $this->addReference('creationType_5', $creationType4);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['testing_unit'];
    }
}
