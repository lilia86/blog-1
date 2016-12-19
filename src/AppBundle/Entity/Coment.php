<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Coment.
 *
 * @ORM\Table(name="coment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ComentRepository")
 */
class Coment
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
     * Many Coments have One Post
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="coments")
     */
    private $post;

    /**
     * @var object
     *
     * Many Coments have One User
     *
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Coment", mappedBy="parent_coment", cascade={"persist", "remove"})
     */
    private $child_coments;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="Coment", inversedBy="child_coments")
     */
    private $parent_coment;

    public function __construct()
    {
        $this->child_coments = new ArrayCollection();
    }

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

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Coment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param Coment $coment
     *
     * @return $this
     */
    public function setParentComent(Coment $coment)
    {
        $this->parent_coment = $coment;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildComents()
    {
        return $this->child_coments;
    }
}
