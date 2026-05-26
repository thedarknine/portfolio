<?php

namespace App\DataFixtures;

use App\Entity\Education;
use App\Entity\School;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EducationAutoFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $education0 = new Education();
        $education0->setYear(2021);
        $education0->setDetails('Certification formation Advanced');
        $education0->setSpeciality(null);
        $education0->setMention(null);
        $education0->setType(\App\Enum\EducationType::PROFESSIONAL);
        $education0->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $education0->setUpdatedAt(new \DateTime('2026-05-24 21:47:09'));
        $education0->setTitle('Product Designer');
        $education0->setSlug('design');
        try {
            $education0->setSchool($this->getReference('school_10', School::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($education0);
        $this->addReference('education_8', $education0);

        $education1 = new Education();
        $education1->setYear(2020);
        $education1->setDetails('Certification Scrum Product Owner');
        $education1->setSpeciality(null);
        $education1->setMention(null);
        $education1->setType(\App\Enum\EducationType::PROFESSIONAL);
        $education1->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $education1->setUpdatedAt(new \DateTime('2026-05-24 21:44:41'));
        $education1->setTitle('Responsable de produit agile');
        $education1->setSlug('agile');
        try {
            $education1->setSchool($this->getReference('school_9', School::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($education1);
        $this->addReference('education_9', $education1);

        $education2 = new Education();
        $education2->setYear(2006);
        $education2->setDetails('Stratégies Internet et Pilotage de Projets en Entreprise');
        $education2->setSpeciality('Informatique');
        $education2->setMention('Très bien');
        $education2->setType(\App\Enum\EducationType::UNIVERSITARY);
        $education2->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $education2->setUpdatedAt(new \DateTime('2026-05-24 21:44:05'));
        $education2->setTitle('Master 2 Professionnel SIPPE');
        $education2->setSlug('m2-pro');
        try {
            $education2->setSchool($this->getReference('school_8', School::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($education2);
        $this->addReference('education_10', $education2);

        $education3 = new Education();
        $education3->setYear(2005);
        $education3->setDetails('Modèle, Système, Intelligence');
        $education3->setSpeciality('Systèmes d\'information et de communication');
        $education3->setMention(null);
        $education3->setType(\App\Enum\EducationType::UNIVERSITARY);
        $education3->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $education3->setUpdatedAt(new \DateTime('2026-05-24 21:44:09'));
        $education3->setTitle('DEA Informatique');
        $education3->setSlug('m2-rech');
        try {
            $education3->setSchool($this->getReference('school_7', School::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($education3);
        $this->addReference('education_11', $education3);

        $education4 = new Education();
        $education4->setYear(2004);
        $education4->setDetails('Sciences et Technologies - Mention Informatique');
        $education4->setSpeciality('Classification, data-mining et algorithmique pour les grandes bases');
        $education4->setMention('Bien');
        $education4->setType(\App\Enum\EducationType::UNIVERSITARY);
        $education4->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $education4->setUpdatedAt(new \DateTime('2026-05-24 21:44:13'));
        $education4->setTitle('Maîtrise Informatique');
        $education4->setSlug('maitrise');
        try {
            $education4->setSchool($this->getReference('school_7', School::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($education4);
        $this->addReference('education_12', $education4);

        $education5 = new Education();
        $education5->setYear(2003);
        $education5->setDetails('Sciences et Technologies - Mention Informatique');
        $education5->setSpeciality('Optimisation et technologies de l\'information');
        $education5->setMention('Assez bien');
        $education5->setType(\App\Enum\EducationType::UNIVERSITARY);
        $education5->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $education5->setUpdatedAt(new \DateTime('2026-05-24 21:44:16'));
        $education5->setTitle('Licence Informatique');
        $education5->setSlug('licence');
        try {
            $education5->setSchool($this->getReference('school_7', School::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($education5);
        $this->addReference('education_13', $education5);

        $education6 = new Education();
        $education6->setYear(2002);
        $education6->setDetails('Mathématiques, informatique et applications aux sciences');
        $education6->setSpeciality('Sciences de la Vie et de la Terre');
        $education6->setMention(null);
        $education6->setType(\App\Enum\EducationType::UNIVERSITARY);
        $education6->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $education6->setUpdatedAt(new \DateTime('2026-05-24 21:44:19'));
        $education6->setTitle('DEUG MIAS');
        $education6->setSlug('deug');
        try {
            $education6->setSchool($this->getReference('school_6', School::class));
        } catch (\OutOfBoundsException $e) {
            // Reference does not exist yet or target entity was skipped
        }
        $manager->persist($education6);
        $this->addReference('education_14', $education6);

        $manager->flush();
    }

    /**
     * @return array<int, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [
            SchoolAutoFixture::class,
        ];
    }
}
