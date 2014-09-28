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
        $wordTypes = array('name', 'sentence', 'preposition', 'verb', 'adverb', 'article', 'conjunction', 'pronoun', 'adjective');

        foreach ($wordTypes as $wordType) {
            $wordTypeObj = new WordType();
            $wordTypeObj->setValue($wordType);
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