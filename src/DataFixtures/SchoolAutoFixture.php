<?php

namespace App\DataFixtures;

use App\Entity\School;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SchoolAutoFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $school0 = new School();
        $school0->setName('Université d\'Orléans');
        $school0->setCity('Bourges');
        $school0->setDepartment(18);
        $school0->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $school0->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $school0->setLabel('univ-orleans');
        $school0->setLogo('univ-orleans.png');
        $manager->persist($school0);
        $this->addReference('school_6', $school0);

        $school1 = new School();
        $school1->setName('Université Blaise Pascal');
        $school1->setCity('Aubière');
        $school1->setDepartment(63);
        $school1->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $school1->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $school1->setLabel('ubp-clermont');
        $school1->setLogo('ubp.png');
        $manager->persist($school1);
        $this->addReference('school_7', $school1);

        $school2 = new School();
        $school2->setName('Université Blaise Pascal - Pôle Universitaire Vichy ');
        $school2->setCity('Vichy');
        $school2->setDepartment(3);
        $school2->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $school2->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $school2->setLabel('ubp-vichy');
        $school2->setLogo('pole-ubp-vichy.png');
        $manager->persist($school2);
        $this->addReference('school_8', $school2);

        $school3 = new School();
        $school3->setName('Actinuum');
        $school3->setCity('Paris');
        $school3->setDepartment(75);
        $school3->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $school3->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $school3->setLabel('actinuum');
        $school3->setLogo('actinuum.png');
        $manager->persist($school3);
        $this->addReference('school_9', $school3);

        $school4 = new School();
        $school4->setName('The Design Crew');
        $school4->setCity('Paris');
        $school4->setDepartment(75);
        $school4->setCreatedAt(new \DateTime('2026-05-23 18:10:55'));
        $school4->setUpdatedAt(new \DateTime('2026-05-23 18:10:55'));
        $school4->setLabel('design-crew');
        $school4->setLogo('the-design-crew.png');
        $manager->persist($school4);
        $this->addReference('school_10', $school4);

        $manager->flush();
    }
}
