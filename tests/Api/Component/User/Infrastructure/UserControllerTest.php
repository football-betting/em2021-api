<?php declare(strict_types=1);

namespace App\Tests\Api\Component\User\Infrastructure;

use App\DataFixtures\AppFixtures;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private ?AppFixtures $appFixtures;
    private ?EntityManager $entityManager;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        $this->entityManager = self::$container
            ->get('doctrine')
            ->getManager();

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
        $this->appFixtures->load($this->entityManager);
        $user = '{"email":"ninja@em2021.com","password":"pass123"}';

        $this->client->request(
            'POST',
            '/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $user
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
        self::assertSame('{"success":true,"data":{"id":1,"username":"ninja","email":"ninja@em2021.com"}}', $response->getContent());
    }
}
