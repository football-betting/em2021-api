<?php declare(strict_types=1);

namespace App\Tests\Acceptance\Component\User\Infrastructure;

use App\DataFixtures\AppFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
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

    public function testRegister()
    {
        $user = [
            "username" => "DarkNinja",
            "email" => "ninja@secret.com",
            "password" => "ninjaIsTheBest",
        ];

        $this->client->request(
            'POST',
            '/auth/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );

        self::assertResponseStatusCodeSame(200);

        $expectedUser = $this->userRepository->find(1);

        self::assertInstanceOf(User::class, $expectedUser);
        self::assertSame($user['email'], $expectedUser->getEmail());
        self::assertSame($user['username'], $expectedUser->getUsername());
        self::assertNotEmpty($expectedUser->getPassword());
    }

    public function testLogin()
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

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);

        self::assertTrue($contents['success']);
        self::assertSame('Bearer '. $userList[1]->getToken(), $contents['token']);
    }

    public function testLoginFailWhenUserNotFound()
    {
        $this->appFixtures->load($this->entityManager);
        $user = [
            "email" => 'user@not_in.db',
            "password" => 'wrong_pass',
        ];


        $this->client->request(
            'POST',
            '/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );

        $this->checkIfResponseIsForLoginFail();
    }

    public function testLoginFailWhenUserHasWrongPassword()
    {
        $userList = $this->appFixtures->load($this->entityManager);
        $user = [
            "email" => $userList[1]->getEmail(),
            "password" => 'wrong_pass',
        ];

        $this->client->request(
            'POST',
            '/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );

        $this->checkIfResponseIsForLoginFail();
    }

    public function testLoginFailWhenEmptyRequest()
    {
        $this->appFixtures->load($this->entityManager);
        $this->client->request(
            'POST',
            '/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([])
        );

        $this->checkIfResponseIsForLoginFail();
    }

    public function testLoginFailWhenEmptyPasswordRequest()
    {
        $userList = $this->appFixtures->load($this->entityManager);
        $user = [
            "email" => $userList[1]->getEmail(),
        ];

        $this->client->request(
            'POST',
            '/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );

        $this->checkIfResponseIsForLoginFail();
    }

    private function checkIfResponseIsForLoginFail(): void
    {
        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);

        self::assertSame('email or password is wrong.', $contents['message']);
        self::assertFalse($contents['success']);
        self::assertArrayNotHasKey('token', $contents);
    }
}
