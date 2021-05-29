<?php declare(strict_types=1);

namespace App\Tests\Acceptance\Component\User\Infrastructure;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    /**
     * @var \App\Repository\UserRepository|object|null
     */
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
            "username" => "ninja",
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
}
