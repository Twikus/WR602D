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
            $subscription = $manager->getRepository(Subscription::class)->findOneBy(['title' => 'Free']);
            $user->setFirstname('John');
            $user->setLastname('Doe');
            $user->setUserCredits($subscription->getPdfLimit());
            $user->setEmail('john.doe' . $i . '@example.com');
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    'password'
                )
            );
            $user->setSubscription($subscription);
            $user->setUserCredits($subscription->getPdfLimit());
            // Persist the user object
            $manager->persist($user);
        }
        // Flush all persisted objects
        $manager->flush();
    }
}
