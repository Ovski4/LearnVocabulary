<?php

namespace Ovski\LanguageBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ovski\LanguageBundle\Entity\Learning;

class LoadLearningData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $learnings = array(
            array('language1' => 'spanish',  'language2' => 'french'),
            array('language1' => 'german',   'language2' => 'french'),
        );

        foreach($learnings as $learning) {
            $learningObj = new Learning();
            $learningObj->setLanguage1($this->getReference($learning['language1']));
            $learningObj->setLanguage2($this->getReference($learning['language2']));
            $learningObj->addUser($this->getReference('baptiste'));
            $manager->persist($learningObj);
            $this->addReference($learningObj->getSlug(), $learningObj);
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
