<?php

namespace Ovski\LanguageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Learning
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity({"language1", "language2"})
 */
class Learning
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
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Ovski\LanguageBundle\Entity\Language")
     * @ORM\JoinColumn(nullable=false)
     */
    private $language1;

    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Ovski\LanguageBundle\Entity\Language")
     * @ORM\JoinColumn(nullable=false)
     */
    private $language2;

    /**
     * Learning to string
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s - %s', $this->language1->getName(), $this->language2->getName());
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
     * Set language1
     *
     * @param \Ovski\LanguageBundle\Entity\Language $language1
     * @return Learning
     */
    public function setLanguage1(\Ovski\LanguageBundle\Entity\Language $language1)
    {
        $this->language1 = $language1;

        return $this;
    }

    /**
     * Get language1
     *
     * @return \Ovski\LanguageBundle\Entity\Language 
     */
    public function getLanguage1()
    {
        return $this->language1;
    }

    /**
     * Set language2
     *
     * @param \Ovski\LanguageBundle\Entity\Language $language2
     * @return Learning
     */
    public function setLanguage2(\Ovski\LanguageBundle\Entity\Language $language2)
    {
        $this->language2 = $language2;

        return $this;
    }

    /**
     * Get language2
     *
     * @return \Ovski\LanguageBundle\Entity\Language 
     */
    public function getLanguage2()
    {
        return $this->language2;
    }
}
