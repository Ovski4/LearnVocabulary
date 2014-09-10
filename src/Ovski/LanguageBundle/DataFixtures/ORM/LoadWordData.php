<?php

namespace Ovski\LanguageBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ovski\LanguageBundle\Entity\Word;

class LoadWordData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // word = { language, wordType, value, [article] }
        $words = array(
            array(
                'language'  => 'french',
                'word_type' => 'adverb',
                'value'     => 'surtout'
            ),
            array(
                'language'  => 'spanish',
                'word_type' => 'adverb',
                'value'     => 'principalmente'
            ),
            array(
                'language'  => 'spanish',
                'word_type' => 'name',
                'value'     => 'pan',
                'article'   => 'el'
            ),
            array(
                'language'  => 'spanish',
                'word_type' => 'name',
                'value'     => 'manzana',
                'article'   => 'la'
            ),
            array(
                'language'  => 'spanish',
                'word_type' => 'name',
                'value'     => 'mujer',
                'article'   => 'la'
            ),
            array(
                'language'  => 'spanish',
                'word_type' => 'name',
                'value'     => 'hombre',
                'article'   => 'el'
            ),
            array(
                'language'  => 'french',
                'word_type' => 'name',
                'value'     => 'pain',
                'article'   => 'le'
            ),
            array(
                'language'  => 'french',
                'word_type' => 'name',
                'value'     => 'pomme',
                'article'   => 'la'
            ),
            array(
                'language'  => 'french',
                'word_type' => 'name',
                'value'     => 'homme',
                'article'   => 'l\''
            ),
            array(
                'language'  => 'french',
                'word_type' => 'name',
                'value'     => 'mère',
                'article'   => 'la'
            )
        );

        foreach($words as $word) {
            $wordObj = new Word();
            $wordObj->setLanguage($this->getReference($word['language']));
            $wordObj->setValue($word['value']);
            $wordObj->setWordType($this->getReference($word['word_type']));
            if ($word['word_type'] == 'name') {
                $wordObj->setArticle($this->getReference(sprintf('%s-%s', $word['language'], $word['article'])));
                $this->setReference(sprintf('%s-%s', $word['article'], $word['value']), $wordObj);
            }
            $this->setReference($word['value'], $wordObj);
            $manager->persist($wordObj);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 5;
    }
}