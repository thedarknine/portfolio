<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CompanyAutoFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $company0 = new Company();
        $company0->setUrl(null);
        $company0->setCreatedAt(new \DateTime('2026-05-27 12:55:08'));
        $company0->setUpdatedAt(new \DateTime('2026-05-27 12:55:08'));
        $company0->setName('Atelier Clay');
        $company0->setSlug('atelier-clay');
        $company0->setLogo('logo.png');
        $company0->setCity('Orléans');
        $company0->setDepartment(45);
        $manager->persist($company0);
        $this->addReference('company_13', $company0);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['testing_unit'];
    }
}
