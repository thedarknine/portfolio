<?php

namespace App\DataFixtures;

use App\Entity\ResourceLink;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ResourceLinkAutoFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $resourceLink0 = new ResourceLink();
        $resourceLink0->setUrl('https://www.linkedin.com/in/carolinenoyer');
        $resourceLink0->setInHero(true);
        $resourceLink0->setCreatedAt(new \DateTime('2026-05-27 16:03:19'));
        $resourceLink0->setUpdatedAt(new \DateTime('2026-05-27 16:03:19'));
        $resourceLink0->setIcon('fa fa-linkedin');
        $resourceLink0->setTitle('LinkedIn');
        $resourceLink0->setSlug('linkedin');
        $resourceLink0->setPosition(0);
        $manager->persist($resourceLink0);
        $this->addReference('resourceLink_1', $resourceLink0);

        $resourceLink1 = new ResourceLink();
        $resourceLink1->setUrl('https://instagram.com/caro.noyer');
        $resourceLink1->setInHero(true);
        $resourceLink1->setCreatedAt(new \DateTime('2026-05-27 16:03:19'));
        $resourceLink1->setUpdatedAt(new \DateTime('2026-05-27 16:03:19'));
        $resourceLink1->setIcon('fa-brands fa-instagram');
        $resourceLink1->setTitle('Instagram');
        $resourceLink1->setSlug('instagram');
        $resourceLink1->setPosition(1);
        $manager->persist($resourceLink1);
        $this->addReference('resourceLink_2', $resourceLink1);

        $resourceLink2 = new ResourceLink();
        $resourceLink2->setUrl('https://docs.carolinenoyer.fr/pdf/cv-cnoyer-seniorbackdev.pdf');
        $resourceLink2->setInHero(true);
        $resourceLink2->setCreatedAt(new \DateTime('2026-05-27 16:03:19'));
        $resourceLink2->setUpdatedAt(new \DateTime('2026-05-27 16:03:19'));
        $resourceLink2->setIcon('fa fa-file-pdf');
        $resourceLink2->setTitle('CV format PDF');
        $resourceLink2->setSlug('cv-format-pdf');
        $resourceLink2->setPosition(2);
        $manager->persist($resourceLink2);
        $this->addReference('resourceLink_3', $resourceLink2);

        $resourceLink3 = new ResourceLink();
        $resourceLink3->setUrl('https://open.spotify.com/playlist/3g3bePO9Jddljzgx5wS4IM?si=c4a65cd590b04b94');
        $resourceLink3->setInHero(false);
        $resourceLink3->setCreatedAt(new \DateTime('2026-05-27 16:03:19'));
        $resourceLink3->setUpdatedAt(new \DateTime('2026-05-27 16:03:19'));
        $resourceLink3->setIcon('fa-brands fa-spotify');
        $resourceLink3->setTitle('Spotify');
        $resourceLink3->setSlug('spotify');
        $resourceLink3->setPosition(3);
        $manager->persist($resourceLink3);
        $this->addReference('resourceLink_4', $resourceLink3);

        $resourceLink4 = new ResourceLink();
        $resourceLink4->setUrl('https://github.com/thedarknine');
        $resourceLink4->setInHero(false);
        $resourceLink4->setCreatedAt(new \DateTime('2026-05-27 16:03:19'));
        $resourceLink4->setUpdatedAt(new \DateTime('2026-05-27 16:03:19'));
        $resourceLink4->setIcon('fa fa-github-alt');
        $resourceLink4->setTitle('GitHub');
        $resourceLink4->setSlug('github');
        $resourceLink4->setPosition(4);
        $manager->persist($resourceLink4);
        $this->addReference('resourceLink_5', $resourceLink4);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['portfolio'];
    }
}
