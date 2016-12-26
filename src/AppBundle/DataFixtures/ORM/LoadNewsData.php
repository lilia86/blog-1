<?php

namespace AppBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\News;

class LoadNewsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $news1 = new News();
        $news1->setTitle('Some news');
        $news1->setBody('Cras consequat iaculis lorem, id vehicula erat mattis quis. Vivamus laoreet 
         velit justo, in ven e natis purus pretium sit amet. Praesent lectus tortor, tincidu nt in consectetur 
         vestibulum, ultrices nec neque. Praesent nec sagittis mauris. Fusce convallis nunc neque. Integer 
         egestas aliquam interdum. Nulla ante diam, interdum nec tempus eu, feugiat vel erat. Integer aliquam 
         mi quis accum san porta.Quisque nec turpis nisi. Proin lobortis nisl ut orci euismod, et lobortis est 
         scelerisque.');
        $news1->setSource('http://geekhub.ck.ua');

        $news2 = new News();
        $news2->setTitle('More news');
        $news2->setBody('Cras consequat iaculis lorem, id vehicula erat mattis quis. Vivamus laoreet 
         velit justo, in ven e natis purus pretium sit amet. Praesent lectus tortor, tincidu nt in consectetur 
         vestibulum, ultrices nec neque. Praesent nec sagittis mauris. Fusce convallis nunc neque. Integer 
         egestas aliquam interdum. Nulla ante diam, interdum nec tempus eu, feugiat vel erat. Integer aliquam 
         mi quis accum san porta.Quisque nec turpis nisi. Proin lobortis nisl ut orci euismod, et lobortis est 
         scelerisque.');
        $news2->setSource('http://geekhub.ck.ua');

        $news3 = new News();
        $news3->setTitle('Interesting news');
        $news3->setBody('Cras consequat iaculis lorem, id vehicula erat mattis quis. Vivamus laoreet 
         velit justo, in ven e natis purus pretium sit amet. Praesent lectus tortor, tincidu nt in consectetur 
         vestibulum, ultrices nec neque. Praesent nec sagittis mauris. Fusce convallis nunc neque. Integer 
         egestas aliquam interdum. Nulla ante diam, interdum nec tempus eu, feugiat vel erat. Integer aliquam 
         mi quis accum san porta.Quisque nec turpis nisi. Proin lobortis nisl ut orci euismod, et lobortis est 
         scelerisque.');
        $news3->setSource('http://geekhub.ck.ua');

        $manager->persist($news1);
        $manager->persist($news2);
        $manager->persist($news3);

        $manager->flush();

        $this->addReference('news1', $news1);
        $this->addReference('news2', $news2);
        $this->addReference('news3', $news3);
    }

    public function getOrder()
    {
        return 7;
    }
}
