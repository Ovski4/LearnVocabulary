<?php

namespace Ovski\LanguageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Word
 *
 * @ORM\Table(name="ovski_word")
 * @ORM\Entity
 */
class Word
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="Ovski\LanguageBundle\Entity\Article")
     * @ORM\JoinColumn(nullable=true)
     */
    private $article;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Ovski\LanguageBundle\Entity\WordType")
     * @ORM\JoinColumn(nullable=false, name="word_type_id")
     */
    private $wordType;

    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Ovski\LanguageBundle\Entity\Language")
     * @ORM\JoinColumn(nullable=false)
     */
    private $language;

    /**
     * @ORM\ManyToMany(targetEntity="Ovski\UserBundle\Entity\User", inversedBy="words")
     * @ORM\JoinTable(name="ovski_user_word")
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * Word to string
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
     * Set article
     *
     * @param string $article
     * @return Word
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return string 
     */
    public function getArticle()
    {
        return $this->article;
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

    /**
     * Set wordType
     *
     * @param \Ovski\LanguageBundle\Entity\WordType $wordType
     * @return Word
     */
    public function setWordType(\Ovski\LanguageBundle\Entity\WordType $wordType)
    {
        $this->wordType = $wordType;

        return $this;
    }

    /**
     * Get wordType
     *
     * @return \Ovski\LanguageBundle\Entity\WordType 
     */
    public function getWordType()
    {
        return $this->wordType;
    }

    /**
     * Add user
     *
     * @param \Ovski\UserBundle\Entity\User $user
     * @return Word
     */
    public function addUser(\Ovski\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Ovski\UserBundle\Entity\User $user
     */
    public function removeUser(\Ovski\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}
