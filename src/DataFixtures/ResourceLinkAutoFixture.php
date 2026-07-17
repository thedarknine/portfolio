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
        $resourceLink0->setCreatedAt(new \DateTime('2026-05-26 07:41:46'));
        $resourceLink0->setUpdatedAt(new \DateTime('2026-06-26 20:05:00'));
        $resourceLink0->setIcon('fa7-brands:linkedin-in');
        $resourceLink0->setTitle('LinkedIn');
        $resourceLink0->setSlug('linkedin');
        $resourceLink0->setPosition(0);
        $resourceLink0->setPublished(true);
        $manager->persist($resourceLink0);
        $this->addReference('resourceLink_1', $resourceLink0);

        $resourceLink1 = new ResourceLink();
        $resourceLink1->setUrl('https://instagram.com/caro.noyer');
        $resourceLink1->setInHero(true);
        $resourceLink1->setCreatedAt(new \DateTime('2026-05-26 07:43:20'));
        $resourceLink1->setUpdatedAt(new \DateTime('2026-06-26 20:05:01'));
        $resourceLink1->setIcon('fa7-brands:instagram');
        $resourceLink1->setTitle('Instagram');
        $resourceLink1->setSlug('instagram');
        $resourceLink1->setPosition(1);
        $resourceLink1->setPublished(true);
        $manager->persist($resourceLink1);
        $this->addReference('resourceLink_2', $resourceLink1);

        $resourceLink2 = new ResourceLink();
        $resourceLink2->setUrl('https://docs.carolinenoyer.fr/cnoyer-cv-productowner.pdf');
        $resourceLink2->setInHero(true);
        $resourceLink2->setCreatedAt(new \DateTime('2026-05-26 07:43:48'));
        $resourceLink2->setUpdatedAt(new \DateTime('2026-06-26 20:04:54'));
        $resourceLink2->setIcon('fa7-solid:file-pdf');
        $resourceLink2->setTitle('CV format PDF');
        $resourceLink2->setSlug('cv-format-pdf');
        $resourceLink2->setPosition(3);
        $resourceLink2->setPublished(true);
        $manager->persist($resourceLink2);
        $this->addReference('resourceLink_3', $resourceLink2);

        $resourceLink3 = new ResourceLink();
        $resourceLink3->setUrl('https://open.spotify.com/playlist/3g3bePO9Jddljzgx5wS4IM?si=c4a65cd590b04b94');
        $resourceLink3->setInHero(false);
        $resourceLink3->setCreatedAt(new \DateTime('2026-05-26 07:46:49'));
        $resourceLink3->setUpdatedAt(new \DateTime('2026-06-26 20:04:54'));
        $resourceLink3->setIcon('fa7-brands:spotify');
        $resourceLink3->setTitle('Spotify');
        $resourceLink3->setSlug('spotify');
        $resourceLink3->setPosition(4);
        $resourceLink3->setPublished(true);
        $manager->persist($resourceLink3);
        $this->addReference('resourceLink_4', $resourceLink3);

        $resourceLink4 = new ResourceLink();
        $resourceLink4->setUrl('https://github.com/thedarknine');
        $resourceLink4->setInHero(false);
        $resourceLink4->setCreatedAt(new \DateTime('2026-05-26 07:47:14'));
        $resourceLink4->setUpdatedAt(new \DateTime('2026-06-26 20:04:54'));
        $resourceLink4->setIcon('fa7-brands:github-alt');
        $resourceLink4->setTitle('GitHub');
        $resourceLink4->setSlug('github');
        $resourceLink4->setPosition(5);
        $resourceLink4->setPublished(true);
        $manager->persist($resourceLink4);
        $this->addReference('resourceLink_5', $resourceLink4);

        $resourceLink5 = new ResourceLink();
        $resourceLink5->setUrl('https://carolinenoyer.fr/contact');
        $resourceLink5->setInHero(true);
        $resourceLink5->setCreatedAt(new \DateTime('2026-06-26 20:04:51'));
        $resourceLink5->setUpdatedAt(new \DateTime('2026-06-26 20:06:31'));
        $resourceLink5->setIcon('fa7-solid:paper-plane');
        $resourceLink5->setTitle('Contact');
        $resourceLink5->setSlug('contact');
        $resourceLink5->setPosition(2);
        $resourceLink5->setPublished(true);
        $manager->persist($resourceLink5);
        $this->addReference('resourceLink_26', $resourceLink5);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['portfolio'];
    }
}
