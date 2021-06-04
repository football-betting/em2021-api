<?php declare(strict_types=1);

namespace App\Tests\Acceptance\Component\User\Infrastructure;

use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private ?AppFixtures $appFixtures;
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

        /** @var AppFixtures appFixtures */
        $this->appFixtures = static::$container->get(AppFixtures::class);
    }


    protected function tearDown(): void
    {
        $this->appFixtures->truncateTable($this->entityManager);

        $this->entityManager->close();
        $this->entityManager = null;

        parent::tearDown();
    }

    public function testInfoWithSecurityCheck()
    {
        $userList = $this->appFixtures->load($this->entityManager);
        $user = [
            "email" => $userList[1]->getEmail(),
            "password" => AppFixtures::DATA['rockstar']['password'],
        ];

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
        self::assertSame($userList[1]->getId(), $contents['data']['id']);
        self::assertSame($userList[1]->getUsername(), $contents['data']['username']);
        self::assertSame($userList[1]->getEmail(), $contents['data']['email']);
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
        $userList = $this->appFixtures->load($this->entityManager);
        $user = [
            "email" => $userList[1]->getEmail(),
            "password" => AppFixtures::DATA['rockstar']['password'],
        ];

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
        $userList = $this->appFixtures->load($this->entityManager);
        $user = [
            "email" => $userList[0]->getEmail(),
            "password" => AppFixtures::DATA['ninja']['password'],
        ];

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
}
