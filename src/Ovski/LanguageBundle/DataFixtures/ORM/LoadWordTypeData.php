<?php

namespace Ovski\LanguageBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ovski\LanguageBundle\Entity\WordType;

class LoadWordTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $wordTypes = array(
            array(
                'default_value' => 'name',
                'french_value'  => 'nom'
            ),
            array(
                'default_value' => 'sentence',
                'french_value'  => 'phrase'
            ),
            array(
                'default_value' => 'preposition',
                'french_value'  => 'préposition'
            ),
            array(
                'default_value' => 'verb',
                'french_value'  => 'verbe'
            ),
            array(
                'default_value' => 'adverb',
                'french_value'  => 'adverb'
            ),
            array(
                'default_value' => 'interjection',
                'french_value'  => 'interjection'
            ),
            array(
                'default_value' => 'article',
                'french_value'  => 'article'
            ),
            array(
                'default_value' => 'conjunction',
                'french_value'  => 'conjonction'
            ),
            array(
                'default_value' => 'pronoun',
                'french_value'  => 'pronom'
            ),
            array(
                'default_value' => 'adjective',
                'french_value'  => 'adjectif'
            )
        );

        $repository = $manager->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        foreach ($wordTypes as $wordType) {
            $wordTypeObj = new WordType();
            $wordTypeObj->setValue($wordType['default_value']);
            $repository->translate($wordTypeObj, 'value', 'fr', $wordType['french_value']);
            $manager->persist($wordTypeObj);
            $this->addReference($wordTypeObj->getValue(), $wordTypeObj);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}