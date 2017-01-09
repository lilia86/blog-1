<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Point.
 *
 * @ORM\Table(name="point")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostPointRepository")
 */
class PostPoint
{
    use Timestampable;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var object
     *
     * Many Points have One Post
     * @Assert\Type("object")
     * @Assert\Valid
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="points")
     */
    private $post;

    /**
     * @var object
     *
     * Many Coments have One User
     * @Assert\Type("object")
     * @Assert\Valid
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $user;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set post.
     *
     * @param Post object $post
     *
     * @return Coment
     */
    public function setPost(Post $post)
    {
        $this->post = $post;
        $post->setCount();

        return $this;
    }

    /**
     * Get post.
     *
     * @return object
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set user.
     *
     * @param User object $user
     *
     * @return Coment
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return object
     */
    public function getUser()
    {
        return $this->user;
    }
}
