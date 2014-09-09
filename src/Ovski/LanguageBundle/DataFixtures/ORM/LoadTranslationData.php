<?php

namespace Ovski\LanguageBundle\DataFixtures\ORM;

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
        $translation1 = new Translation();
        $translation1->setLearning($this->getReference('french-spanish'));
        $translation1->setWord1($this->getReference('la-manzana'));
        $translation1->setWord2($this->getReference('la-pomme'));
        $manager->persist($translation1);

        $translation2 = new Translation();
        $translation2->setLearning($this->getReference('french-spanish'));
        $translation2->setWord1($this->getReference('principalmente'));
        $translation2->setWord2($this->getReference('surtout'));
        $manager->persist($translation2);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 6;
    }
}