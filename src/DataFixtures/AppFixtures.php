<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Tests\Fixtures\UserTips;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public const DATA = [
        'ninja' => [
            'email' => 'ninja@em2021.com',
            'username' => UserTips::USER,
            'password' => 'pass123',
            'tip1' => 'FR',
            'tip2' => 'EN',
        ],
        'rockstar' => [
            'email' => 'rockstar@em2021.com',
            'username' => 'rockstar',
            'password' => 'pass123',
            'tip1' => 'DE',
            'tip2' => 'EN',
        ]
    ];

    private ParameterBagInterface $params;

    /**
     * @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * @param \Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface $params
     */
    public function __construct(ParameterBagInterface $params, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->params = $params;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     *
     * @return User[]
     */
    public function load(ObjectManager $manager)
    {
        $userList = [];

        $this->truncateTable($manager);

        $user = new User();
        $user->setEmail(self::DATA['ninja']['email']);
        $user->setUsername(self::DATA['ninja']['username']);
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, self::DATA['ninja']['password']));
        $user->setTokenTimeAllowed(new \DateTime('+ 15 Minutes'));
        $user->setTip1(self::DATA['ninja']['tip1']);
        $user->setTip2(self::DATA['ninja']['tip2']);

        $payload = [
            "userId" => 1,
        ];

        $jwt = JWT::encode($payload, $this->params->get('kernel.secret'), 'HS256');
        $user->setToken($jwt);
        $userList[] = $user;
        $manager->persist($user);

        $user = new User();
        $user->setEmail(self::DATA['rockstar']['email']);
        $user->setUsername(self::DATA['rockstar']['username']);
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, self::DATA['rockstar']['password']));
        $user->setTokenTimeAllowed(new \DateTime('+ 15 Minutes'));
        $user->setTip1(self::DATA['rockstar']['tip1']);
        $user->setTip2(self::DATA['rockstar']['tip2']);

        $payload = [
            "userId" => 2,
        ];

        $jwt = JWT::encode($payload, $this->params->get('kernel.secret'), 'HS256');
        $user->setToken($jwt);

        $userList[] = $user;

        $manager->persist($user);


        $manager->flush();

        return $userList;
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
