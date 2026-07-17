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
        $company0->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $company0->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $company0->setName('LIMOS (Laboratoire d\'Informatique, de Modélisation et d\'Optimisation des Systèmes)');
        $company0->setSlug('limos');
        $company0->setLogo('limos.png');
        $company0->setCity('Aubière');
        $company0->setDepartment(63);
        $manager->persist($company0);
        $this->addReference('company_9', $company0);

        $company1 = new Company();
        $company1->setUrl(null);
        $company1->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $company1->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $company1->setName('Periscope Créations');
        $company1->setSlug('periscope-creations');
        $company1->setLogo('periscope.png');
        $company1->setCity('Clermont-Ferrand');
        $company1->setDepartment(63);
        $manager->persist($company1);
        $this->addReference('company_10', $company1);

        $company2 = new Company();
        $company2->setUrl(null);
        $company2->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $company2->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $company2->setName('ActifDesign');
        $company2->setSlug('actif-design');
        $company2->setLogo('actif-design.png');
        $company2->setCity('Les Martres-de-Veyre');
        $company2->setDepartment(63);
        $manager->persist($company2);
        $this->addReference('company_11', $company2);

        $company3 = new Company();
        $company3->setUrl(null);
        $company3->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $company3->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $company3->setName('Allegorithmic');
        $company3->setSlug('allegorithmic');
        $company3->setLogo('allegorithmic.png');
        $company3->setCity('Clermont-Ferrand');
        $company3->setDepartment(63);
        $manager->persist($company3);
        $this->addReference('company_12', $company3);

        $company4 = new Company();
        $company4->setUrl(null);
        $company4->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $company4->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $company4->setName('Mutualité Française du Puy-de-Dôme');
        $company4->setSlug('mfpdd');
        $company4->setLogo('mutualite.png');
        $company4->setCity('Clermont-Ferrand');
        $company4->setDepartment(63);
        $manager->persist($company4);
        $this->addReference('company_13', $company4);

        $company5 = new Company();
        $company5->setUrl(null);
        $company5->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $company5->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $company5->setName('Coffreo');
        $company5->setSlug('coffreo');
        $company5->setLogo('coffreo.png');
        $company5->setCity('Aubière');
        $company5->setDepartment(63);
        $manager->persist($company5);
        $this->addReference('company_14', $company5);

        $company6 = new Company();
        $company6->setUrl(null);
        $company6->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $company6->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $company6->setName('Perfect Memory');
        $company6->setSlug('perfect');
        $company6->setLogo('perfect-memory.png');
        $company6->setCity('Chamalières');
        $company6->setDepartment(63);
        $manager->persist($company6);
        $this->addReference('company_15', $company6);

        $company7 = new Company();
        $company7->setUrl(null);
        $company7->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $company7->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $company7->setName('Leviia');
        $company7->setSlug('leviia');
        $company7->setLogo('leviia.png');
        $company7->setCity('Remote');
        $company7->setDepartment(63);
        $manager->persist($company7);
        $this->addReference('company_16', $company7);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['portfolio'];
    }
}
