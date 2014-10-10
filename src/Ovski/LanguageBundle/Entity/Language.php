<?php

namespace Ovski\LanguageBundle\Entity;

use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
 * @ORM\Table(name="ovski_language", uniqueConstraints={
 *     @ORM\UniqueConstraint(
 *         name="unique_language_idx",
 *         columns={"name"}
 *     )
 * })
 * @ORM\Entity
 */
class Language
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="require_articles", type="boolean")
     */
    protected $requireArticles;

    /**
     * @var Article
     *
     * @ORM\OneToMany(targetEntity="Ovski\LanguageBundle\Entity\Article", mappedBy="language")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $articles;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    protected $locale;

    /**
     * Language to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Language
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set requireArticles
     *
     * @param boolean $requireArticles
     * @return Language
     */
    public function setRequireArticles($requireArticles)
    {
        $this->requireArticles = $requireArticles;

        return $this;
    }

    /**
     * Get requireArticles
     *
     * @return boolean 
     */
    public function requireArticles()
    {
        return $this->requireArticles;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get requireArticles
     *
     * @return boolean 
     */
    public function getRequireArticles()
    {
        return $this->requireArticles;
    }

    /**
     * Add articles
     *
     * @param \Ovski\LanguageBundle\Entity\Article $articles
     * @return Language
     */
    public function addArticle(\Ovski\LanguageBundle\Entity\Article $articles)
    {
        $this->articles[] = $articles;

        return $this;
    }

    /**
     * Remove articles
     *
     * @param \Ovski\LanguageBundle\Entity\Article $articles
     */
    public function removeArticle(\Ovski\LanguageBundle\Entity\Article $articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles()
    {
        return $this->articles;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
