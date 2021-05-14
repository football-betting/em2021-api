<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('ninja@em2021.com');
        $user->setUsername('ninja');
        $user->setPassword('pass');

        $manager->persist($user);

        $user = new User();
        $user->setEmail('rockstar@em2021.com');
        $user->setUsername('rockstar');
        $user->setPassword('pass');

        $manager->persist($user);

        $manager->flush();
    }
}
