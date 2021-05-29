<?php declare(strict_types=1);

namespace App\Tests\Acceptance\Component\User\Infrastructure;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserControllerTest extends WebTestCase
{
    private ?UserPasswordEncoderInterface $userPasswordEncoder;
    private ?UserRepository $userRepository;
    private ?EntityManager $entityManager;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        $this->entityManager = self::$container
            ->get('doctrine')
            ->getManager();

        $this->userRepository = static::$container->get(UserRepository::class);
        $this->userPasswordEncoder = static::$container->get(UserPasswordEncoderInterface::class);
    }


    protected function tearDown(): void
    {
        $this->entityManager->getConnection()->executeStatement('TRUNCATE user');

        $this->entityManager->close();
        $this->entityManager = null;

        parent::tearDown();
    }

    public function testInfoWithSecurityCheck()
    {
        $user = [
            "email" => "ninja@secret.com",
            "password" => "ninjaIsTheBest",
        ];
        $this->saveUser($user);

        $this->client->request(
            'POST',
            '/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );

        $token = json_decode($this->client->getResponse()->getContent(), true)['token'];
        $this->client->restart();

        $this->client->request(
            'GET',
            '/api/user/info',
            [],
            [],
            [
                'Authorization' => $token,
            ]
        );

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);
        self::assertArrayHasKey('data', $contents);
        self::assertSame(1, $contents['data']['id']);
        self::assertSame('ninja', $contents['data']['username']);
        self::assertSame($user['email'], $contents['data']['email']);
    }

    public function testInfoWithSecurityNotValid()
    {
        $this->client->request(
            'GET',
            '/api/user/info',
            [],
            [],
            [
                'Authorization' => 'not_found_token',
            ]
        );

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);
        self::assertSame('Wrong number of segments', $contents['message']);
    }

    public function testInfoWithSecurityFailWhenUserNotFound()
    {
        $user = [
            "email" => "ninja@secret.com",
            "password" => "ninjaIsTheBest",
        ];
        $this->saveUser($user);

        $this->client->request(
            'POST',
            '/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );

        $token = json_decode($this->client->getResponse()->getContent(), true)['token'];
        $this->client->restart();

        $this->entityManager->getConnection()->executeStatement('TRUNCATE user');


        $this->client->request(
            'GET',
            '/api/user/info',
            [],
            [],
            [
                'Authorization' => $token,
            ]
        );

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);
        self::assertSame('Bad credentials.', $contents['message']);
    }

    public function testInfoWithSecurityFailWhenDateTokenIsOld()
    {
        $user = [
            "email" => "ninja@secret.com",
            "password" => "ninjaIsTheBest",
        ];
        $this->saveUser($user);

        $this->client->request(
            'POST',
            '/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );

        $token = json_decode($this->client->getResponse()->getContent(), true)['token'];
        $this->client->restart();

        $userEntity = $this->userRepository->find(1);
        $userEntity->setTokenTimeAllowed(new \DateTime());
        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();

        $this->client->request(
            'GET',
            '/api/user/info',
            [],
            [],
            [
                'Authorization' => $token,
            ]
        );

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);
        self::assertSame('Token time is expired', $contents['message']);
    }

    private function saveUser(array $user): void
    {
        $userEntity = new User();
        $userEntity->setUsername('ninja');
        $userEntity->setEmail($user['email']);
        $userEntity->setPassword($this->userPasswordEncoder->encodePassword($userEntity, $user['password']));

        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();
    }

}
