<?php

namespace App\DataFixtures;

use App\Entity\Experience;
use App\Entity\Company;
use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExperienceAutoFixture extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $experience0 = new Experience();
        $experience0->setSubtitle(null);
        $experience0->setSummary(null);
        $experience0->setDescription('Description');
        $experience0->setStartDate(new \DateTime('2020-01-01 00:00:00'));
        $experience0->setEndDate(new \DateTime('2021-01-01 00:00:00'));
        $experience0->setCreatedAt(new \DateTime('2026-05-27 12:55:08'));
        $experience0->setUpdatedAt(new \DateTime('2026-05-27 12:55:08'));
        $experience0->setTitle('Web Dev');
        $experience0->setSlug('web-dev');
        try {
            $experience0->setCompany($this->getReference('company_13', Company::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($experience0);
        $this->addReference('experience_17', $experience0);

        $manager->flush();
    }

    /**
     * @return array<int, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [
            CompanyAutoFixture::class,
            SkillAutoFixture::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['testing_unit'];
    }
}
