<?php declare(strict_types=1);

namespace App\Tests\Acceptance\Component\User\Infrastructure;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthControllerTest extends WebTestCase
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

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);

        self::assertSame(1, $contents['id']);
        self::assertSame($user['email'], $contents['user']);

        $expectedUser = $this->userRepository->find(1);

        self::assertInstanceOf(User::class, $expectedUser);
        self::assertSame($user['email'], $expectedUser->getEmail());
        self::assertSame($user['username'], $expectedUser->getUsername());
        self::assertNotEmpty($expectedUser->getPassword());
    }

    public function testLogin()
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

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);

        self::assertSame('success', $contents['message']);
        self::assertNotEmpty($contents['token']);
    }

    public function testLoginFailWhenUserNotFound()
    {
        $user = [
            "email" => "ninja@secret.com",
            "password" => "ninjaIsTheBest",
        ];
        $this->saveUser($user);

        $user['email'] = 'not@found.com';

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
        $user = [
            "email" => "ninja@secret.com",
            "password" => "ninjaIsTheBest",
        ];
        $this->saveUser($user);

        $user['password'] = 'i_dont_remember';

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
            json_encode([])
        );

        $this->checkIfResponseIsForLoginFail();
    }

    public function testLoginFailWhenEmptyPasswordRequest()
    {
        $user = [
            "email" => "ninja@secret.com",
            "password" => "ninjaIsTheBest",
        ];
        $this->saveUser($user);

        unset($user['password']);

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

    /**
     * @param array $user
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function saveUser(array $user): void
    {
        $userEntity = new User();
        $userEntity->setUsername('DarkNinja');
        $userEntity->setEmail($user['email']);
        $userEntity->setPassword($this->userPasswordEncoder->encodePassword($userEntity, $user['password']));

        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();
    }

    private function checkIfResponseIsForLoginFail(): void
    {
        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);

        self::assertSame('email or password is wrong.', $contents['message']);
        self::assertArrayNotHasKey('token', $contents);
    }
}
