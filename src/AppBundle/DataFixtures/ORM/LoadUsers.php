<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\User;

class LoadUsers implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $users = array(
            array(
                'username' => 'admin',
                'password' => 'admin',
                'email' => 'admin@mail.com',
                'roles' => array('ROLE_ADMIN'),
                'enabled' => true
            ),
            array(
                'username' => 'joe',
                'password' => 'joe',
                'email' => 'joe@mail.com',
                'roles' => array('ROLE_USER'),
                'enabled' => true
            )
        );

        foreach ($users as $value) {
            $user = new User();
            $user->setUsername($value['username']);
            $password = $value['password'];
            $encoder = $this->container->get('security.password_encoder');
            $password = $encoder->encodePassword($user, $password);
            $user->setPassword($password);
            $user->setEmail($value['email']);
            $user->setRoles($value['roles']);
            $user->setEnabled($value['enabled']);
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}