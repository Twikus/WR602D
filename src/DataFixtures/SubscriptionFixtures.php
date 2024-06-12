<?php

namespace App\DataFixtures;

use App\Entity\Subscription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SubscriptionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $subscription = new Subscription();
        $subscription->setTitle('Free');
        $subscription->setDescription('Free subscription');
        $subscription->setPdfLimit(5);
        $subscription->setPrice(0.00);
        $subscription->setMedia('free.png');
        $manager->persist($subscription);

        $subscription = new Subscription();
        $subscription->setTitle('Basic');
        $subscription->setDescription('Basic subscription');
        $subscription->setPdfLimit(10);
        $subscription->setPrice(9.99);
        $subscription->setMedia('basic.png');
        $manager->persist($subscription);

        $subscription = new Subscription();
        $subscription->setTitle('Premium');
        $subscription->setDescription('Premium subscription');
        $subscription->setPdfLimit(100);
        $subscription->setPrice(19.99);
        $subscription->setMedia('premium.png');
        $manager->persist($subscription);

        $manager->flush();
    }
}
