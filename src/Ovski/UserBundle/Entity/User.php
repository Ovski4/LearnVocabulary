<?php

namespace Ovski\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="ovski_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer
     *
     * @Assert\Range(
     *      min = 5,
     *      max = 500,
     *      minMessage = "The minimum is {{ limit }}",
     *      maxMessage = "The maximum is {{ limit }}"
     * )
     * @ORM\Column(name="max_items_per_page", type="integer")
     */
    private $maxitemsPerPage;

    /**
     * @ORM\ManyToMany(targetEntity="Ovski\LanguageBundle\Entity\Word", mappedBy="users")
     */
    private $words;

    /**
     * @ORM\ManyToMany(targetEntity="Ovski\LanguageBundle\Entity\Learning", mappedBy="users")
     */
    private $learnings;

    /**
     * @ORM\OneToMany(targetEntity="Ovski\LanguageBundle\Entity\Translation", mappedBy="user")
     */
    private $translations;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->maxitemsPerPage = 20;
        $this->translations = new ArrayCollection();
        $this->learnings = new ArrayCollection();
        $this->words = new ArrayCollection();
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
     * Set maxitemsPerPage
     *
     * @param integer $maxitemsPerPage
     * @return Parameters
     */
    public function setMaxitemsPerPage($maxitemsPerPage)
    {
        $this->maxitemsPerPage = $maxitemsPerPage;

        return $this;
    }

    /**
     * Get maxitemsPerPage
     *
     * @return integer
     */
    public function getMaxitemsPerPage()
    {
        return $this->maxitemsPerPage;
    }

    /**
     * Add word
     *
     * @param \Ovski\LanguageBundle\Entity\Word $word
     * @return User
     */
    public function addWord(\Ovski\LanguageBundle\Entity\Word $word)
    {
        $this->words[] = $word;

        return $this;
    }

    /**
     * Remove word
     *
     * @param \Ovski\LanguageBundle\Entity\Word $word
     */
    public function removeWord(\Ovski\LanguageBundle\Entity\Word $word)
    {
        $this->words->removeElement($word);
    }

    /**
     * Get words
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWords()
    {
        return $this->words;
    }

    /**
     * Add learning
     *
     * @param \Ovski\LanguageBundle\Entity\Learning $learning
     * @return User
     */
    public function addLearning(\Ovski\LanguageBundle\Entity\Learning $learning)
    {
        $this->learnings[] = $learning;

        return $this;
    }

    /**
     * Remove learning
     *
     * @param \Ovski\LanguageBundle\Entity\Learning $learning
     */
    public function removeLearning(\Ovski\LanguageBundle\Entity\Learning $learning)
    {
        $this->learnings->removeElement($learning);
    }

    /**
     * Get learnings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLearnings()
    {
        return $this->learnings;
    }

    /**
     * Add translation
     *
     * @param \Ovski\LanguageBundle\Entity\Translation $translation
     * @return User
     */
    public function addTranslation(\Ovski\LanguageBundle\Entity\Translation $translation)
    {
        $this->translations[] = $translation;

        return $this;
    }

    /**
     * Remove translation
     *
     * @param \Ovski\LanguageBundle\Entity\Translation $translation
     */
    public function removeTranslation(\Ovski\LanguageBundle\Entity\Translation $translation)
    {
        $this->translations->removeElement($translation);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranslations()
    {
        return $this->translations;
    }
}
