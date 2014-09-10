<?php

namespace Ovski\LanguageBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ovski\LanguageBundle\Entity\Language;

class LoadLanguageData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $languages = array(
            array('name' => 'german',  'require_articles' => true),
            array('name' => 'english', 'require_articles' => false),
            array('name' => 'spanish', 'require_articles' => true),
            array('name' => 'french',  'require_articles' => true)
        );

        foreach ($languages as $language) {
            $languageObj = new Language();
            $languageObj->setName($language['name']);
            $languageObj->setRequireArticles($language['require_articles']);
            $manager->persist($languageObj);
            $this->addReference($languageObj->getName(), $languageObj);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}