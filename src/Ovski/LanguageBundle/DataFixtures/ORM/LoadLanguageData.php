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
            array(
                'default_name' => 'german',
                'french_name' => 'allemand',
                'require_articles' => true
            ),
            array(
                'default_name' => 'english',
                'french_name' => 'anglais',
                'require_articles' => false
            ),
            array(
                'default_name' => 'spanish',
                'french_name' => 'espagnol',
                'require_articles' => true
            ),
            array(
                'default_name' => 'french',
                'french_name' => 'français',
                'require_articles' => true
            )
        );

        $repository = $manager->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        foreach ($languages as $language) {
            $languageObj = new Language();
            $languageObj->setName($language['default_name']);
            $languageObj->setRequireArticles($language['require_articles']);
            $repository->translate($languageObj, 'name', 'fr', $language['french_name']);
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
        return 2;
    }
}
