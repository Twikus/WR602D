<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Subscription;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private  UserPasswordHasherInterface $userPasswordHasher; 

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setFirstname('John');
            $user->setLastname('Doe');
            $user->setEmail('john.doe' . $i . '@example.com');
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    'password'
                )
            );
            $user->setRoles(['ROLE_USER']);
            $user->setSubscriptionId($manager->getRepository(Subscription::class)->findOneBy(['title' => 'Free']));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
