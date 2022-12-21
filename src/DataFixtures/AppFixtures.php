<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        //Master user
        $masterUser = new User();
        $masterUser
            ->setEmail('master@admincms.com.uy')
            ->setRoles(['ROLE_SUPER_ADMIN'])
            ->setIsVerified(true)
            ->setName("Master")
            ->setUsername("master@admincms.com.uy")
            ->setPassword(
                $this->passwordHasher->hashPassword(
                    $masterUser,
                    'master@admincms.com.uy',
                ),
            );
        $manager->persist($masterUser);

        $manager->flush();
    }
}
