<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Tests\Fixtures\UserTips;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AppFixtures extends Fixture
{
    private ParameterBagInterface $params;

    /**
     * @param \Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface $params
     */
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function load(ObjectManager $manager)
    {
        $this->truncateTable($manager);

        $user = new User();
        $user->setEmail('ninja@em2021.com');
        $user->setUsername(UserTips::USER);
        $user->setPassword('pass');
        $user->setTokenTimeAllowed(new \DateTime('+ 15 Minutes'));

        $payload = [
            "userId" => 1,
        ];

        $jwt = JWT::encode($payload, $this->params->get('kernel.secret'), 'HS256');
        $user->setToken($jwt);

        $manager->persist($user);

        $user = new User();
        $user->setEmail('rockstar@em2021.com');
        $user->setUsername('rockstar');
        $user->setPassword('pass');
        $user->setTokenTimeAllowed(new \DateTime('+ 15 Minutes'));

        $payload = [
            "userId" => 2,
        ];

        $jwt = JWT::encode($payload, $this->params->get('kernel.secret'), 'HS256');
        $user->setToken($jwt);

        $manager->persist($user);

        $manager->flush();
    }

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     */
    public function truncateTable(ObjectManager $manager): void
    {
        $connection = $manager->getConnection();
        $platform = $connection->getDatabasePlatform();

        $connection->executeUpdate($platform->getTruncateTableSQL('user'));
    }
}
