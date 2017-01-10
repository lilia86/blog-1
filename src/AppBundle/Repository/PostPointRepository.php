<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use AppBundle\Entity\Post;
use AppBundle\Entity\PostPoint;

/**
 * PostPointRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostPointRepository extends \Doctrine\ORM\EntityRepository
{
    public function saveNotRepeatedPoint(User $user, Post $post)
    {
        $query = $this->getEntityManager()->createQuery('SELECT c FROM AppBundle:PostPoint c WHERE c.user = :user AND c.post = :post');
        $query->setParameters(array('user' => $user, 'post' => $post));

        $repeatedPoint =  $query->getResult();

        if (!$repeatedPoint) {
            $point = new PostPoint();
            $point->setUser($user);
            $point->setPost($post);
            $this->getEntityManager()->persist($point);
            $this->getEntityManager()->flush();

        } else {
            foreach ($repeatedPoint as $item) {
                $this->getEntityManager()->remove($item);
                $this->getEntityManager()->flush();
            }
        }
    }
}
