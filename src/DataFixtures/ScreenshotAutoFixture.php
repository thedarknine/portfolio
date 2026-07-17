<?php

namespace App\DataFixtures;

use App\Entity\Screenshot;
use App\Entity\Project;
use App\DataFixtures\ProjectAutoFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ScreenshotAutoFixture extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $screenshot0 = new Screenshot();
        $screenshot0->setFilename('fairepart-gus.jpg');
        $screenshot0->setDescription('Faire part de naissance');
        $screenshot0->setCreatedAt(new \DateTime('2026-07-05 08:03:31'));
        $screenshot0->setUpdatedAt(new \DateTime('2026-07-05 20:02:34'));
        $screenshot0->setPosition(4);
        try {
            $screenshot0->setProject($this->getReference('project_13', Project::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($screenshot0);
        $this->addReference('screenshot_2', $screenshot0);

        $screenshot1 = new Screenshot();
        $screenshot1->setFilename('fairepart-elea.jpg');
        $screenshot1->setDescription('Faire part de naissance');
        $screenshot1->setCreatedAt(new \DateTime('2026-07-05 08:03:31'));
        $screenshot1->setUpdatedAt(new \DateTime('2026-07-05 20:02:34'));
        $screenshot1->setPosition(5);
        try {
            $screenshot1->setProject($this->getReference('project_13', Project::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($screenshot1);
        $this->addReference('screenshot_3', $screenshot1);

        $screenshot2 = new Screenshot();
        $screenshot2->setFilename('website-randos.png');
        $screenshot2->setDescription('Détail rando');
        $screenshot2->setCreatedAt(new \DateTime('2026-07-05 08:09:49'));
        $screenshot2->setUpdatedAt(new \DateTime('2026-07-05 20:02:34'));
        $screenshot2->setPosition(2);
        try {
            $screenshot2->setProject($this->getReference('project_11', Project::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($screenshot2);
        $this->addReference('screenshot_4', $screenshot2);

        $screenshot3 = new Screenshot();
        $screenshot3->setFilename('website-teamtanesc.png');
        $screenshot3->setDescription('Maquette page d\'accueil');
        $screenshot3->setCreatedAt(new \DateTime('2026-07-05 08:10:41'));
        $screenshot3->setUpdatedAt(new \DateTime('2026-07-05 08:10:41'));
        $screenshot3->setPosition(0);
        try {
            $screenshot3->setProject($this->getReference('project_12', Project::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($screenshot3);
        $this->addReference('screenshot_5', $screenshot3);

        $screenshot4 = new Screenshot();
        $screenshot4->setFilename('test.jpg');
        $screenshot4->setDescription('Plop');
        $screenshot4->setCreatedAt(new \DateTime('2026-07-05 20:02:34'));
        $screenshot4->setUpdatedAt(new \DateTime('2026-07-05 20:02:34'));
        $screenshot4->setPosition(1);
        try {
            $screenshot4->setProject($this->getReference('project_36', Project::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($screenshot4);
        $this->addReference('screenshot_6', $screenshot4);

        $manager->flush();
    }

    /**
     * @return array<int, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [
            ProjectAutoFixture::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['portfolio'];
    }
}
