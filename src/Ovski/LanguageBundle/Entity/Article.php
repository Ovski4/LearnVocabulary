<?php

namespace Ovski\LanguageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="ovski_article", uniqueConstraints={
 *     @ORM\UniqueConstraint(
 *         name="unique_article_idx",
 *         columns={"value", "language_id"}
 *     )
 * })
 * @ORM\Entity
 */
class Article
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
     * @ORM\Column(name="value", type="string", length=255)
     */
    protected $value;

    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Ovski\LanguageBundle\Entity\Language", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $language;

    /**
     * Article to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->value;
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
     * Set value
     *
     * @param string $value
     * @return Word
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set language
     *
     * @param \Ovski\LanguageBundle\Entity\Language $language
     * @return Word
     */
    public function setLanguage(\Ovski\LanguageBundle\Entity\Language $language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \Ovski\LanguageBundle\Entity\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }
}