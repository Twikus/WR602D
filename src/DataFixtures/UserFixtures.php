<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Subscription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setFirstname('John');
            $user->setLastname('Doe');
            $user->setEmail('john.doe' . $i . '@example.com');
            $user->setPassword('password');
            $user->setRoles(['ROLE_USER']);
            $user->setSubscriptionId($manager->getRepository(Subscription::class)->findOneBy(['title' => 'Free']));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
