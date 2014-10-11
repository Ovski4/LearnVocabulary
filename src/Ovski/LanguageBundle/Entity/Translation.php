<?php

namespace Ovski\LanguageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ovski\LanguageBundle\Validator\Constraints as OvskiAssert;

/**
 * Translation
 *
 * @ORM\Table(name="ovski_translation", uniqueConstraints={
 *     @ORM\UniqueConstraint(
 *         name="unique_translation_idx",
 *         columns={"word1_id", "word2_id", "learning_id", "user_id", "word_type_id"}
 *     )
 * })
 * @ORM\Entity(repositoryClass="Ovski\LanguageBundle\Repository\TranslationRepository")
 * @OvskiAssert\TranslationRequireArticles
 * @OvskiAssert\TranslationUnique
 */
class Translation
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var Word
     *
     * @ORM\ManyToOne(targetEntity="Ovski\LanguageBundle\Entity\Word", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $word1;

    /**
     * @var Word
     *
     * @ORM\ManyToOne(targetEntity="Ovski\LanguageBundle\Entity\Word", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $word2;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Ovski\LanguageBundle\Entity\WordType")
     * @ORM\JoinColumn(nullable=false, name="word_type_id")
     */
    protected $wordType;

    /**
     * @var Learning
     *
     * @ORM\ManyToOne(targetEntity="Ovski\LanguageBundle\Entity\Learning")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $learning;

    /**
     * @var Learning
     *
     * @ORM\ManyToOne(targetEntity="Ovski\UserBundle\Entity\User", inversedBy="translations")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="is_starred", type="boolean", nullable=false)
     */
    protected $isStarred;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->isStarred = false;
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Translation
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set word1
     *
     * @param \Ovski\LanguageBundle\Entity\Word $word1
     * @return Translation
     */
    public function setWord1(\Ovski\LanguageBundle\Entity\Word $word1)
    {
        $this->word1 = $word1;

        return $this;
    }

    /**
     * Get word1
     *
     * @return \Ovski\LanguageBundle\Entity\Word 
     */
    public function getWord1()
    {
        return $this->word1;
    }

    /**
     * Set word2
     *
     * @param \Ovski\LanguageBundle\Entity\Word $word2
     * @return Translation
     */
    public function setWord2(\Ovski\LanguageBundle\Entity\Word $word2)
    {
        $this->word2 = $word2;

        return $this;
    }

    /**
     * Get word2
     *
     * @return \Ovski\LanguageBundle\Entity\Word 
     */
    public function getWord2()
    {
        return $this->word2;
    }

    /**
     * Set learning
     *
     * @param \Ovski\LanguageBundle\Entity\Learning $learning
     * @return Translation
     */
    public function setLearning(\Ovski\LanguageBundle\Entity\Learning $learning)
    {
        $this->learning = $learning;

        return $this;
    }

    /**
     * Get learning
     *
     * @return \Ovski\LanguageBundle\Entity\Learning 
     */
    public function getLearning()
    {
        return $this->learning;
    }

    /**
     * Set wordType
     *
     * @param \Ovski\LanguageBundle\Entity\WordType $wordType
     * @return Translation
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
     * Set user
     *
     * @param \Ovski\UserBundle\Entity\User $user
     * @return Translation
     */
    public function setUser(\Ovski\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Ovski\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set isStarred
     *
     * @param boolean $isStarred
     * @return Translation
     */
    public function setIsStarred($isStarred)
    {
        $this->isStarred = $isStarred;

        return $this;
    }

    /**
     * Get isStarred
     *
     * @return boolean 
     */
    public function getIsStarred()
    {
        return $this->isStarred;
    }
}
