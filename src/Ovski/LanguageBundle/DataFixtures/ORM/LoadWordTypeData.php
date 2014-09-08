<?php

namespace Acme\HelloBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ovski\LanguageBundle\Entity\WordType;

class LoadWordTypeData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $preposition = new WordType();
        $preposition->setValue('preposition');
        $manager->persist($preposition);

        $name = new WordType();
        $name->setValue('name');
        $manager->persist($name);

        $verb = new WordType();
        $verb->setValue('verb');
        $manager->persist($verb);

        $manager->flush();
    }
}