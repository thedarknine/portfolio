<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class UserAutoFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $user0 = new User();
        $user0->setEmail('studio@carolinenoyer.fr');
        $user0->setRoles(array (
  0 => 'ROLE_ADMIN',
  1 => 'ROLE_USER',
));
        $user0->setPassword('$2y$13$ScoyG/gFu91tJZ50V/DzfOUlPc0fYWPbulVg0bngHIL0Lg1810Mde');
        $user0->setGoogleAuthenticatorSecret('NWOEUUTN2F3SJAHDXMABEXCF5BT4S5EOG4T65VL2MXMJGSY5MUBA');
        $manager->persist($user0);
        $this->addReference('user_1', $user0);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['portfolio'];
    }
}
