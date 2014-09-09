<?php

namespace Ovski\LanguageBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ovski\LanguageBundle\Entity\Article;

class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $articles = array(
            'spanish' => 'el',
            'spanish' => 'la',
            'german'  => 'der',
            'german'  => 'die',
            'german'  => 'das',
            'french'  => 'le',
            'french'  => 'la',
            'french'  => 'l\''
        );

        foreach ($articles as $language => $value) {
            $articleObj = new Article();
            $articleObj->setLanguage($this->getReference($language));
            $articleObj->setValue($value);
            $manager->persist($articleObj);
            $this->addReference(sprintf('%s-%s', $language, $value), $articleObj);
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