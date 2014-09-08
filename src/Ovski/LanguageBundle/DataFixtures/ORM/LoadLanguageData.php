<?php

namespace Acme\HelloBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ovski\LanguageBundle\Entity\Language;

class LoadLanguageData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $german = new Language();
        $german->setName('german');
        $manager->persist($german);

        $english = new Language();
        $english->setName('english');
        $manager->persist($english);

        $spanish = new Language();
        $spanish->setName('spanish');
        $manager->persist($spanish);

        $french = new Language();
        $french->setName('french');
        $manager->persist($french);

        $manager->flush();
    }
}