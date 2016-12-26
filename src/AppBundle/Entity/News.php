<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * News.
 *
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NewsRepository")
 */
class News extends ArticlesSuperClass
{
    use Timestampable;

    /**
     * @var string
     * @Assert\Url(
     *    checkDNS = true
     * )
     * @ORM\Column(name="source", type="string")
     */
    private $source;

    /**
     * @var ArrayCollection
     *                      One Post have Many Coments
     * @ORM\OneToMany(targetEntity="Coment", mappedBy="post", cascade={"persist", "remove"})
     */
    private $coments;

    public function __construct()
    {
        $this->coments = new ArrayCollection();
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
     * Set source.
     *
     * @param string $source
     *
     * @return News
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }
}
