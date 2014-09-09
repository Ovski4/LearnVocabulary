<?php

namespace Ovski\LanguageBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ovski\LanguageBundle\Entity\Article;

class LoadWordData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /*
        $articleObj = new Article();
        $articleObj->setLanguage($this->getReference($language));
        $articleObj->setValue($value);
        $manager->persist($articleObj);
        */

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