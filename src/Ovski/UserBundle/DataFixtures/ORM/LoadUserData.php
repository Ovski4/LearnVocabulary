<?php

namespace Ovski\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ovski\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $users = array(
            'baptiste',
            'lise'
        );

        foreach ($users as $i => $name) {
            $user = new User();
            $user->setUsername($name);
            $user->setEmail($name . '@example.com');
            $user->setEnabled(TRUE);
            $encodedPw = $this->container
                ->get('security.encoder_factory')
                ->getEncoder($user)
                ->encodePassword($name, $user->getSalt())
            ;
            $user->setPassword($encodedPw);
            $this->addReference($name, $user);

            $manager->persist($user);
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