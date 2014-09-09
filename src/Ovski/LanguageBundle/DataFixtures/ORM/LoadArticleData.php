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
            array(
                'language' => 'spanish',
                'value' => 'el'
            ),
            array(
                'language' => 'spanish',
                'value' => 'la'
            ),
            array(
                'language' => 'german',
                'value' => 'der'
            ),
            array(
                'language' => 'german',
                'value' => 'die'
            ),
            array(
                'language' => 'german',
                'value' => 'das'
            ),
            array(
                'language' => 'french',
                'value' => 'le'
            ),
            array(
                'language' => 'french',
                'value' => 'la'
            ),
            array(
                'language' => 'french',
                'value' => 'l\''
            )
        );

        foreach ($articles as $article) {
            $articleObj = new Article();
            $articleObj->setLanguage($this->getReference($article['language']));
            $articleObj->setValue($article['value']);
            $manager->persist($articleObj);
            $this->addReference(sprintf('%s-%s', $article['language'], $article['value']), $articleObj);
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