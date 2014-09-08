<?php

namespace Ovski\LanguageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Translation
 *
 * @ORM\Table()
 * @ORM\Entity
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
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var Word
     *
     * @ORM\ManyToOne(targetEntity="Ovski\LanguageBundle\Entity\Word")
     * @ORM\JoinColumn(nullable=false)
     */
    private $word1;

    /**
     * @var Word
     *
     * @ORM\ManyToOne(targetEntity="Ovski\LanguageBundle\Entity\Word")
     * @ORM\JoinColumn(nullable=false)
     */
    private $word2;

    /**
     * @var Learning
     *
     * @ORM\ManyToOne(targetEntity="Ovski\LanguageBundle\Entity\Learning")
     * @ORM\JoinColumn(nullable=false)
     */
    private $learning;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
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
}
