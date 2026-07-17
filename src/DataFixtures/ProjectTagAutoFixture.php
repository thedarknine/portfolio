<?php

namespace App\DataFixtures;

use App\Entity\ProjectTag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectTagAutoFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $projectTag0 = new ProjectTag();
        $projectTag0->setCreatedAt(new \DateTime('2026-07-04 15:59:44'));
        $projectTag0->setUpdatedAt(new \DateTime('2026-07-04 21:23:26'));
        $projectTag0->setName('Adobe Premiere');
        $projectTag0->setSlug('adobe-premiere');
        $projectTag0->setPublished(true);
        $projectTag0->setPosition(3);
        $manager->persist($projectTag0);
        $this->addReference('projectTag_1', $projectTag0);

        $projectTag1 = new ProjectTag();
        $projectTag1->setCreatedAt(new \DateTime('2026-07-04 16:07:08'));
        $projectTag1->setUpdatedAt(new \DateTime('2026-07-04 21:23:26'));
        $projectTag1->setName('iMovie');
        $projectTag1->setSlug('imovie');
        $projectTag1->setPublished(true);
        $projectTag1->setPosition(4);
        $manager->persist($projectTag1);
        $this->addReference('projectTag_3', $projectTag1);

        $projectTag2 = new ProjectTag();
        $projectTag2->setCreatedAt(new \DateTime('2026-07-04 18:14:53'));
        $projectTag2->setUpdatedAt(new \DateTime('2026-07-04 21:23:26'));
        $projectTag2->setName('Adobe Illustrator');
        $projectTag2->setSlug('adobe-illustrator');
        $projectTag2->setPublished(true);
        $projectTag2->setPosition(5);
        $manager->persist($projectTag2);
        $this->addReference('projectTag_4', $projectTag2);

        $projectTag3 = new ProjectTag();
        $projectTag3->setCreatedAt(new \DateTime('2026-07-04 18:15:08'));
        $projectTag3->setUpdatedAt(new \DateTime('2026-07-04 21:23:26'));
        $projectTag3->setName('Figma');
        $projectTag3->setSlug('figma');
        $projectTag3->setPublished(true);
        $projectTag3->setPosition(10);
        $manager->persist($projectTag3);
        $this->addReference('projectTag_5', $projectTag3);

        $projectTag4 = new ProjectTag();
        $projectTag4->setCreatedAt(new \DateTime('2026-07-04 18:15:15'));
        $projectTag4->setUpdatedAt(new \DateTime('2026-07-04 21:23:26'));
        $projectTag4->setName('Canva');
        $projectTag4->setSlug('canva');
        $projectTag4->setPublished(true);
        $projectTag4->setPosition(11);
        $manager->persist($projectTag4);
        $this->addReference('projectTag_6', $projectTag4);

        $projectTag5 = new ProjectTag();
        $projectTag5->setCreatedAt(new \DateTime('2026-07-04 19:48:48'));
        $projectTag5->setUpdatedAt(new \DateTime('2026-07-04 21:23:26'));
        $projectTag5->setName('PHP');
        $projectTag5->setSlug('php');
        $projectTag5->setPublished(true);
        $projectTag5->setPosition(0);
        $manager->persist($projectTag5);
        $this->addReference('projectTag_7', $projectTag5);

        $projectTag6 = new ProjectTag();
        $projectTag6->setCreatedAt(new \DateTime('2026-07-04 19:48:52'));
        $projectTag6->setUpdatedAt(new \DateTime('2026-07-04 21:23:26'));
        $projectTag6->setName('HTML/CSS');
        $projectTag6->setSlug('htmlcss');
        $projectTag6->setPublished(true);
        $projectTag6->setPosition(2);
        $manager->persist($projectTag6);
        $this->addReference('projectTag_8', $projectTag6);

        $projectTag7 = new ProjectTag();
        $projectTag7->setCreatedAt(new \DateTime('2026-07-04 19:48:57'));
        $projectTag7->setUpdatedAt(new \DateTime('2026-07-04 21:23:26'));
        $projectTag7->setName('MySQL');
        $projectTag7->setSlug('mysql');
        $projectTag7->setPublished(true);
        $projectTag7->setPosition(1);
        $manager->persist($projectTag7);
        $this->addReference('projectTag_9', $projectTag7);

        $projectTag8 = new ProjectTag();
        $projectTag8->setCreatedAt(new \DateTime('2026-07-04 21:20:52'));
        $projectTag8->setUpdatedAt(new \DateTime('2026-07-04 21:23:26'));
        $projectTag8->setName('Java');
        $projectTag8->setSlug('java');
        $projectTag8->setPublished(true);
        $projectTag8->setPosition(14);
        $manager->persist($projectTag8);
        $this->addReference('projectTag_10', $projectTag8);

        $projectTag9 = new ProjectTag();
        $projectTag9->setCreatedAt(new \DateTime('2026-07-04 21:20:59'));
        $projectTag9->setUpdatedAt(new \DateTime('2026-07-04 21:23:34'));
        $projectTag9->setName('XML');
        $projectTag9->setSlug('xml');
        $projectTag9->setPublished(true);
        $projectTag9->setPosition(13);
        $manager->persist($projectTag9);
        $this->addReference('projectTag_11', $projectTag9);

        $projectTag10 = new ProjectTag();
        $projectTag10->setCreatedAt(new \DateTime('2026-07-04 21:21:30'));
        $projectTag10->setUpdatedAt(new \DateTime('2026-07-04 21:23:26'));
        $projectTag10->setName('Librairies PDF');
        $projectTag10->setSlug('librairies-pdf');
        $projectTag10->setPublished(true);
        $projectTag10->setPosition(16);
        $manager->persist($projectTag10);
        $this->addReference('projectTag_12', $projectTag10);

        $projectTag11 = new ProjectTag();
        $projectTag11->setCreatedAt(new \DateTime('2026-07-04 21:21:56'));
        $projectTag11->setUpdatedAt(new \DateTime('2026-07-04 21:23:26'));
        $projectTag11->setName('XHTML');
        $projectTag11->setSlug('xhtml');
        $projectTag11->setPublished(true);
        $projectTag11->setPosition(15);
        $manager->persist($projectTag11);
        $this->addReference('projectTag_13', $projectTag11);

        $projectTag12 = new ProjectTag();
        $projectTag12->setCreatedAt(new \DateTime('2026-07-04 21:22:32'));
        $projectTag12->setUpdatedAt(new \DateTime('2026-07-04 21:25:07'));
        $projectTag12->setName('C++');
        $projectTag12->setSlug('c-plusplus');
        $projectTag12->setPublished(true);
        $projectTag12->setPosition(6);
        $manager->persist($projectTag12);
        $this->addReference('projectTag_14', $projectTag12);

        $projectTag13 = new ProjectTag();
        $projectTag13->setCreatedAt(new \DateTime('2026-07-04 21:22:54'));
        $projectTag13->setUpdatedAt(new \DateTime('2026-07-04 21:25:07'));
        $projectTag13->setName('Standard Template Library (STL)');
        $projectTag13->setSlug('standard-template-library-stl');
        $projectTag13->setPublished(true);
        $projectTag13->setPosition(7);
        $manager->persist($projectTag13);
        $this->addReference('projectTag_15', $projectTag13);

        $projectTag14 = new ProjectTag();
        $projectTag14->setCreatedAt(new \DateTime('2026-07-04 21:23:10'));
        $projectTag14->setUpdatedAt(new \DateTime('2026-07-04 21:23:26'));
        $projectTag14->setName('Data Mining');
        $projectTag14->setSlug('data-mining');
        $projectTag14->setPublished(true);
        $projectTag14->setPosition(9);
        $manager->persist($projectTag14);
        $this->addReference('projectTag_16', $projectTag14);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['portfolio'];
    }
}
