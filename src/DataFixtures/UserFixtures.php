<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('abc@gmail.com');
        $user->setUsername('demo carribian');
        $user->setRoles(['ROLE_USER']);
        $user->getAboutMe(['demo user']);

        $user->setPassword($this->passwordEncoder->encodePassword(
                         $user,
                         'abc' // new password
                     ));
        $manager->persist($user);
        $manager->flush();

        $user2 = new User();
        $user2->setEmail('cat@gmail.com');
        $user2->setUsername('Mao Alter');
        $user2->setRoles(['ROLE_USER']);
        $user2->getAboutMe(['demo user 2']);

        $user2->setPassword($this->passwordEncoder->encodePassword(
            $user2,
            'abc' // new password
        ));
        $manager->persist($user2);
        $manager->flush();
    }
}
