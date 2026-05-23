<?php

namespace App\DataFixtures;

use App\Entity\ExperienceLink;
use App\Entity\Experience;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExperienceLinkAutoFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $experienceLink0 = new ExperienceLink();
        $experienceLink0->setTitle('Site Leviia');
        $experienceLink0->setUrl('https://leviia.com');
        $experienceLink0->setType(\App\Enum\LinkType::External);
        $experienceLink0->setCreatedAt(new \DateTime('2026-05-23 22:22:04'));
        $experienceLink0->setUpdatedAt(null);
        $experienceLink0->setLabel('site-leviia');
        try {
            $experienceLink0->setExperience($this->getReference('experience_20', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceLink0);
        $this->addReference('experienceLink_1', $experienceLink0);

        $experienceLink1 = new ExperienceLink();
        $experienceLink1->setTitle('Site Perfect');
        $experienceLink1->setUrl('https://perfect-memory.com');
        $experienceLink1->setType(\App\Enum\LinkType::External);
        $experienceLink1->setCreatedAt(new \DateTime('2026-05-23 22:52:07'));
        $experienceLink1->setUpdatedAt(null);
        $experienceLink1->setLabel('site-perfect');
        try {
            $experienceLink1->setExperience($this->getReference('experience_19', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceLink1);
        $this->addReference('experienceLink_2', $experienceLink1);

        $experienceLink2 = new ExperienceLink();
        $experienceLink2->setTitle('Site Coffreo');
        $experienceLink2->setUrl('https://www.coffreo.biz/');
        $experienceLink2->setType(\App\Enum\LinkType::External);
        $experienceLink2->setCreatedAt(new \DateTime('2026-05-23 22:54:40'));
        $experienceLink2->setUpdatedAt(null);
        $experienceLink2->setLabel('site-coffreo');
        try {
            $experienceLink2->setExperience($this->getReference('experience_18', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceLink2);
        $this->addReference('experienceLink_3', $experienceLink2);

        $experienceLink3 = new ExperienceLink();
        $experienceLink3->setTitle('Rapport de stage');
        $experienceLink3->setUrl('cnoyer-rapport-stage-m2pro-2006.pdf');
        $experienceLink3->setType(\App\Enum\LinkType::Pdf);
        $experienceLink3->setCreatedAt(new \DateTime('2026-05-23 22:57:34'));
        $experienceLink3->setUpdatedAt(new \DateTime('2026-05-23 23:08:15'));
        $experienceLink3->setLabel('actifdesign-rapport-stage');
        try {
            $experienceLink3->setExperience($this->getReference('experience_13', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceLink3);
        $this->addReference('experienceLink_4', $experienceLink3);

        $experienceLink4 = new ExperienceLink();
        $experienceLink4->setTitle('Rapport de stage');
        $experienceLink4->setUrl('cnoyer-rapport-stage-dea-2005.pdf');
        $experienceLink4->setType(\App\Enum\LinkType::Pdf);
        $experienceLink4->setCreatedAt(new \DateTime('2026-05-23 22:59:31'));
        $experienceLink4->setUpdatedAt(null);
        $experienceLink4->setLabel('limos-rapport-stage');
        try {
            $experienceLink4->setExperience($this->getReference('experience_11', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceLink4);
        $this->addReference('experienceLink_5', $experienceLink4);

        $experienceLink5 = new ExperienceLink();
        $experienceLink5->setTitle('Publication');
        $experienceLink5->setUrl('cnoyer-article-clones-cla05.pdf');
        $experienceLink5->setType(\App\Enum\LinkType::Pdf);
        $experienceLink5->setCreatedAt(new \DateTime('2026-05-23 23:00:05'));
        $experienceLink5->setUpdatedAt(null);
        $experienceLink5->setLabel('limos-publication');
        try {
            $experienceLink5->setExperience($this->getReference('experience_11', Experience::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experienceLink5);
        $this->addReference('experienceLink_6', $experienceLink5);

        $manager->flush();
    }

    /**
     * @return array<int, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [
            ExperienceAutoFixture::class,
        ];
    }
}
