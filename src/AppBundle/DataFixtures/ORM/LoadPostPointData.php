<?php

namespace AppBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\PostPoint;

class LoadPostPointData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $point1 = new PostPoint();
        $point1->setPost($this->getReference('post1'));
        $point1->setUser($this->getReference('some_user'));

        $point2 = new PostPoint();
        $point2->setPost($this->getReference('post1'));
        $point2->setUser($this->getReference('bloger1'));

        $point3 = new PostPoint();
        $point3->setPost($this->getReference('post1'));
        $point3->setUser($this->getReference('bloger2'));

        $manager->persist($point1);
        $manager->persist($point2);
        $manager->persist($point3);
        $manager->flush();
    }

    public function getOrder()
    {
        return 7;
    }
}
