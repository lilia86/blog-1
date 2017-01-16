<?php

namespace AppBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\UserBloger;
use AppBundle\Entity\UserGuest;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user1 = new UserBloger();
        $user1->setUsername('bloger1');
        $user1->setFirstName('John');
        $user1->setPassword('$2y$13$TH3luMzipFzY7IyKtN9tquzFP7raJ7K6PucJD6kY02ueGTbw786Pu');
        $user1->setEmail('john@gmail.com');
        $user1->setInformation($this->getReference('inf1'));

        $user2 = new UserBloger();
        $user2->setUsername('bloger2');
        $user2->setFirstName('Alice');
        $user2->setPassword('$2y$13$a9ypWxFOfd6jrYmumtpymOVd/BTZoXp2yXIQnln.2AaAUKjXHBnt6');
        $user2->setEmail('alice@gmail.com');
        $user2->setInformation($this->getReference('inf2'));

        $user3 = new UserGuest();
        $user3->setUsername('some_user');
        $user3->setFirstName('Nick');
        $user3->setPassword('$2y$13$DPZCcjZPcEgx.CDvDfzy3.KDp6pz1d5yqj2qtBFGnxm.Suwv2ghqW');
        $user3->setEmail('nick@gmail.com');

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->flush();

        $this->addReference('bloger1', $user1);
        $this->addReference('bloger2', $user2);
        $this->addReference('some_user', $user3);
    }

    public function getOrder()
    {
        return 2;
    }
}
