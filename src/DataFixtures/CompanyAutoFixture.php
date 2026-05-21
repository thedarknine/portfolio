<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyAutoFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $company0 = new Company();
        $company0->setName('LIMOS (Laboratoire d\'Informatique, de Modélisation et d\'Optimisation des Systèmes)');
        $company0->setCity('Aubière');
        $company0->setDepartment(63);
        $company0->setUrl(null);
        $company0->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $company0->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $company0->setLabel('limos');
        $company0->setLogo('limos.png');
        $manager->persist($company0);
        $this->addReference('company_1', $company0);

        $company1 = new Company();
        $company1->setName('Periscope Créations');
        $company1->setCity('Clermont-Ferrand');
        $company1->setDepartment(63);
        $company1->setUrl(null);
        $company1->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $company1->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $company1->setLabel('periscope-creations');
        $company1->setLogo('periscope.png');
        $manager->persist($company1);
        $this->addReference('company_2', $company1);

        $company2 = new Company();
        $company2->setName('ActifDesign');
        $company2->setCity('Les Martres-de-Veyre');
        $company2->setDepartment(63);
        $company2->setUrl(null);
        $company2->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $company2->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $company2->setLabel('actif-design');
        $company2->setLogo('actif-design.png');
        $manager->persist($company2);
        $this->addReference('company_3', $company2);

        $company3 = new Company();
        $company3->setName('Allegorithmic');
        $company3->setCity('Clermont-Ferrand');
        $company3->setDepartment(63);
        $company3->setUrl(null);
        $company3->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $company3->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $company3->setLabel('allegorithmic');
        $company3->setLogo('allegorithmic.png');
        $manager->persist($company3);
        $this->addReference('company_4', $company3);

        $company4 = new Company();
        $company4->setName('Mutualité Française du Puy-de-Dôme');
        $company4->setCity('Clermont-Ferrand');
        $company4->setDepartment(63);
        $company4->setUrl(null);
        $company4->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $company4->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $company4->setLabel('mfpdd');
        $company4->setLogo('mutualite.png');
        $manager->persist($company4);
        $this->addReference('company_5', $company4);

        $company5 = new Company();
        $company5->setName('Coffreo');
        $company5->setCity('Aubière');
        $company5->setDepartment(63);
        $company5->setUrl(null);
        $company5->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $company5->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $company5->setLabel('coffreo');
        $company5->setLogo('coffreo.png');
        $manager->persist($company5);
        $this->addReference('company_6', $company5);

        $company6 = new Company();
        $company6->setName('Perfect Memory');
        $company6->setCity('Chamalières');
        $company6->setDepartment(63);
        $company6->setUrl(null);
        $company6->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $company6->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $company6->setLabel('perfect');
        $company6->setLogo('perfect-memory.png');
        $manager->persist($company6);
        $this->addReference('company_7', $company6);

        $company7 = new Company();
        $company7->setName('Leviia');
        $company7->setCity('Remote');
        $company7->setDepartment(63);
        $company7->setUrl(null);
        $company7->setCreatedAt(new \DateTime('2026-05-20 22:32:22'));
        $company7->setUpdatedAt(new \DateTime('2026-05-20 22:32:22'));
        $company7->setLabel('leviia');
        $company7->setLogo('leviia.png');
        $manager->persist($company7);
        $this->addReference('company_8', $company7);

        $manager->flush();
    }
}
