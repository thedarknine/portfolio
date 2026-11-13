<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTestFixture extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user0 = new User();
        $user0->setEmail('admin@carolinenoyer.fr');
        $user0->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword($user0, 'password_test');
        $user0->setPassword($hashedPassword);
        $manager->persist($user0);
        $this->addReference('user_1', $user0);

        $manager->flush();
    }
}
