<?php declare(strict_types=1);

namespace App\Tests\Api\Component\User\Infrastructure;

use App\DataFixtures\AppFixtures;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    private AppFixtures $appFixtures;
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

    public function getData()
    {
        return [
            [
                'createUser' => false,
                'method' => 'POST',
                'uri' => '/auth/register',
                'content' => '{"username":"DarkNinja","email":"ninja@secret.com","password":"ninjaIsTheBest"}',
                'expectedCode' => 200,
                'expectedResult' => '{"success":true,"data":{"id":1,"username":"DarkNinja"}}',
            ],
            [
                'createUser' => true,
                'method' => 'POST',
                'uri' => '/auth/login',
                'content' => '{"email":"ninja@em2021.com","password":"pass123"}',
                'expectedCode' => 200,
                'expectedResult' => '{"success":true,"token":"Bearer %s"}',
            ],
        ];
    }

    /**
     * @dataProvider getData
     */
    public function testUrl(bool $createUser, string $method, string $uri, string $content, int $expectedCode, string $expectedResult)
    {
        $header = [
            'CONTENT_TYPE' => 'application/json',
        ];

        if ($createUser === true) {
            $userList = $this->appFixtures->load($this->entityManager);

            $expectedResult = json_decode($expectedResult, true);
            $expectedResult['token'] = sprintf($expectedResult['token'], $userList[0]->getToken());
            $expectedResult = json_encode($expectedResult);
        }

        $this->client->request($method, $uri, [], [], $header, $content);

        self::assertResponseStatusCodeSame($expectedCode);

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $responseRequest = $response->getContent();

        self::assertSame($expectedResult, $responseRequest);
    }
}
