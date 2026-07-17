<?php

namespace App\DataFixtures;

use App\Entity\ExperienceLink;
use App\Entity\PageInfo;
use App\Entity\Experience;
use App\DataFixtures\PageInfoAutoFixture;
use App\DataFixtures\ExperienceAutoFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExperienceLinkAutoFixture extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $experienceLink0 = new ExperienceLink();
        $experienceLink0->setUrl('https://leviia.com');
        $experienceLink0->setType(\App\Enum\LinkType::EXTERNAL);
        $experienceLink0->setCreatedAt(new \DateTime('2026-05-23 22:22:04'));
        $experienceLink0->setUpdatedAt(null);
        $experienceLink0->setTitle('Site Leviia');
        $experienceLink0->setSlug('site-leviia');
        try {
            $experienceLink0->setExperience($this->getReference('experience_20', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceLink0);
        $this->addReference('experienceLink_1', $experienceLink0);

        $experienceLink1 = new ExperienceLink();
        $experienceLink1->setUrl('https://perfect-memory.com');
        $experienceLink1->setType(\App\Enum\LinkType::EXTERNAL);
        $experienceLink1->setCreatedAt(new \DateTime('2026-05-23 22:52:07'));
        $experienceLink1->setUpdatedAt(null);
        $experienceLink1->setTitle('Site Perfect');
        $experienceLink1->setSlug('site-perfect');
        try {
            $experienceLink1->setExperience($this->getReference('experience_19', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceLink1);
        $this->addReference('experienceLink_2', $experienceLink1);

        $experienceLink2 = new ExperienceLink();
        $experienceLink2->setUrl('https://www.coffreo.biz/');
        $experienceLink2->setType(\App\Enum\LinkType::EXTERNAL);
        $experienceLink2->setCreatedAt(new \DateTime('2026-05-23 22:54:40'));
        $experienceLink2->setUpdatedAt(null);
        $experienceLink2->setTitle('Site Coffreo');
        $experienceLink2->setSlug('site-coffreo');
        try {
            $experienceLink2->setExperience($this->getReference('experience_18', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceLink2);
        $this->addReference('experienceLink_3', $experienceLink2);

        $experienceLink3 = new ExperienceLink();
        $experienceLink3->setUrl('cnoyer-rapport-stage-m2pro-2006.pdf');
        $experienceLink3->setType(\App\Enum\LinkType::PDF);
        $experienceLink3->setCreatedAt(new \DateTime('2026-05-23 22:57:34'));
        $experienceLink3->setUpdatedAt(new \DateTime('2026-05-23 23:08:15'));
        $experienceLink3->setTitle('Rapport de stage');
        $experienceLink3->setSlug('actifdesign-rapport-stage');
        try {
            $experienceLink3->setExperience($this->getReference('experience_13', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceLink3);
        $this->addReference('experienceLink_4', $experienceLink3);

        $experienceLink4 = new ExperienceLink();
        $experienceLink4->setUrl('cnoyer-rapport-stage-dea-2005.pdf');
        $experienceLink4->setType(\App\Enum\LinkType::PDF);
        $experienceLink4->setCreatedAt(new \DateTime('2026-05-23 22:59:31'));
        $experienceLink4->setUpdatedAt(null);
        $experienceLink4->setTitle('Rapport de stage');
        $experienceLink4->setSlug('limos-rapport-stage');
        try {
            $experienceLink4->setExperience($this->getReference('experience_11', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceLink4);
        $this->addReference('experienceLink_5', $experienceLink4);

        $experienceLink5 = new ExperienceLink();
        $experienceLink5->setUrl('cnoyer-article-clones-cla05.pdf');
        $experienceLink5->setType(\App\Enum\LinkType::PDF);
        $experienceLink5->setCreatedAt(new \DateTime('2026-05-23 23:00:05'));
        $experienceLink5->setUpdatedAt(null);
        $experienceLink5->setTitle('Publication');
        $experienceLink5->setSlug('limos-publication');
        try {
            $experienceLink5->setExperience($this->getReference('experience_11', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceLink5);
        $this->addReference('experienceLink_6', $experienceLink5);

        $experienceLink6 = new ExperienceLink();
        $experienceLink6->setUrl(null);
        $experienceLink6->setType(\App\Enum\LinkType::DETAIL);
        $experienceLink6->setCreatedAt(new \DateTime('2026-07-10 06:52:22'));
        $experienceLink6->setUpdatedAt(new \DateTime('2026-07-11 13:30:00'));
        $experienceLink6->setTitle('Structuration du Pôle Produit');
        $experienceLink6->setSlug('structuration');
        try {
            $experienceLink6->setPage($this->getReference('pageInfo_179', PageInfo::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        try {
            $experienceLink6->setExperience($this->getReference('experience_19', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceLink6);
        $this->addReference('experienceLink_7', $experienceLink6);

        $experienceLink7 = new ExperienceLink();
        $experienceLink7->setUrl(null);
        $experienceLink7->setType(\App\Enum\LinkType::DETAIL);
        $experienceLink7->setCreatedAt(new \DateTime('2026-07-11 22:44:42'));
        $experienceLink7->setUpdatedAt(new \DateTime('2026-07-11 22:50:26'));
        $experienceLink7->setTitle('Brique B2B2C - Exposé™');
        $experienceLink7->setSlug('produit-expose');
        try {
            $experienceLink7->setPage($this->getReference('pageInfo_180', PageInfo::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        try {
            $experienceLink7->setExperience($this->getReference('experience_19', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceLink7);
        $this->addReference('experienceLink_11', $experienceLink7);

        $experienceLink8 = new ExperienceLink();
        $experienceLink8->setUrl(null);
        $experienceLink8->setType(\App\Enum\LinkType::DETAIL);
        $experienceLink8->setCreatedAt(new \DateTime('2026-07-13 21:34:39'));
        $experienceLink8->setUpdatedAt(new \DateTime('2026-07-13 21:34:39'));
        $experienceLink8->setTitle('Refonte app mobile');
        $experienceLink8->setSlug('refonte-app-mobile');
        try {
            $experienceLink8->setPage($this->getReference('pageInfo_182', PageInfo::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        try {
            $experienceLink8->setExperience($this->getReference('experience_18', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceLink8);
        $this->addReference('experienceLink_12', $experienceLink8);

        $experienceLink9 = new ExperienceLink();
        $experienceLink9->setUrl(null);
        $experienceLink9->setType(\App\Enum\LinkType::DETAIL);
        $experienceLink9->setCreatedAt(new \DateTime('2026-07-16 13:03:16'));
        $experienceLink9->setUpdatedAt(new \DateTime('2026-07-16 13:03:16'));
        $experienceLink9->setTitle('Brique B2B - Timesheet');
        $experienceLink9->setSlug('produit-timesheet');
        try {
            $experienceLink9->setPage($this->getReference('pageInfo_181', PageInfo::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        try {
            $experienceLink9->setExperience($this->getReference('experience_17', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceLink9);
        $this->addReference('experienceLink_13', $experienceLink9);

        $manager->flush();
    }

    /**
     * @return array<int, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [
            PageInfoAutoFixture::class,
            ExperienceAutoFixture::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['portfolio'];
    }
}
