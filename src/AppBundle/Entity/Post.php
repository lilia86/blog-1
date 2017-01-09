<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Post.
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 */
class Post extends ArticlesSuperClass
{
    use Timestampable;

    /**
     * @var object
     * @Assert\Type("object")
     * @Assert\Valid
     * @ORM\ManyToOne(targetEntity="UserBloger", inversedBy="posts")
     */
    private $user;

    /**
     * @var object
     * @Assert\Type("object")
     * @Assert\Valid
     * @ORM\ManyToOne(targetEntity="PostCategory", inversedBy="posts")
     */
    private $category;

    /**
     * @var ArrayCollection
     *                      Many Posts have Many Tags
     * @ORM\ManyToMany(targetEntity="PostTag", inversedBy="posts")
     */
    private $tags;

    /**
     * @var ArrayCollection
     *                      One Post have Many Coments
     * @ORM\OneToMany(targetEntity="Coment", mappedBy="post", cascade={"persist", "remove"})
     */
    private $coments;

    /**
     * @var ArrayCollection
     *                      One Post have Many Points
     * @ORM\OneToMany(targetEntity="PostPoint", mappedBy="post", cascade={"persist", "remove"})
     */
    private $points;

    /**
     * @var int
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count = 0;

    public function __construct()
    {
        $this->coments = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->points = new ArrayCollection();
    }

    /**
     * Set user.
     *
     * @param User $user
     *
     * @return Post
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
     * Set category.
     *
     * @param PostCategory $category
     *
     * @return Post
     */
    public function setCategory(PostCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get categoryId.
     *
     * @return object
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param PostTag $tag
     *
     * @return $this
     */
    public function addTag(PostTag $tag)
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addPost($this);
        }

        return $this;
    }

    /**
     * Get tags.
     *
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Get coments.
     *
     * @return ArrayCollection
     */
    public function getComents()
    {
        return $this->coments;
    }

    /**
     * Get coments.
     *
     * @return ArrayCollection
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set count.
     *
     * @return Post
     */
    public function setCount()
    {
        $this->count = $this->count + 1;

        return $this;
    }

    /**
     * Get count.
     *
     * @return object
     */
    public function getCount()
    {
        return $this->count;
    }
}
