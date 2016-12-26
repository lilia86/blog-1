<?php

namespace AppBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\UserData;

class LoadUserInfData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $inf1 = new UserData();
        $inf1->setEmail('john@email.com');

        $inf2 = new UserData();
        $inf2->setEmail('alice@email.com');

        $manager->persist($inf1);
        $manager->persist($inf2);
        $manager->flush();

        $this->addReference('inf1', $inf1);
        $this->addReference('inf2', $inf2);
    }

    public function getOrder()
    {
        return 1;
    }
}
