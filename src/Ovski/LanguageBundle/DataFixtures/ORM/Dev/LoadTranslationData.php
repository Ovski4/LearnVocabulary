<?php

namespace Ovski\LanguageBundle\DataFixtures\ORM\Dev;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ovski\LanguageBundle\Entity\Translation;

class LoadTranslationData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $translations = array(
            array(
                'learning'  => 'french-spanish',
                'word1'     => 'la-manzana',
                'word2'     => 'la-pomme',
                'word_type' => 'name'
            ),
            array(
                'learning'  => 'french-spanish',
                'word1'     => 'principalmente',
                'word2'     => 'surtout',
                'word_type' => 'adverb'
            )
        );

        foreach ($translations as $translation) {
            $translationObj = new Translation();
            $translationObj->setLearning($this->getReference($translation['learning']));
            $translationObj->setWord1($this->getReference($translation['word1']));
            $translationObj->setWord2($this->getReference($translation['word2']));
            $translationObj->setWordType($this->getReference($translation['word_type']));
            $translationObj->setUser($this->getReference('baptiste'));
            $manager->persist($translationObj);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 7;
    }
}