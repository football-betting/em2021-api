<?php declare(strict_types=1);

namespace App\Tests\Api\Component\UserRating\Infrastructure;

use App\DataFixtures\AppFixtures;
use App\DataTransferObject\UserRatingListDataProvider;
use App\Repository\UserRepository;
use App\Service\Redis\RedisService;
use App\Service\RedisKey\RedisKeyService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRatingControllerTest extends WebTestCase
{
    private ?RedisService $redisService;
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
        $this->appFixtures->load($this->entityManager);

        /** @var RedisService redisService */
        $this->redisService = static::$container->get(RedisService::class);

        $userRatingListDataProvider = new UserRatingListDataProvider();
        $userRatingListDataProvider->fromArray([
            "users" => [
                [
                    "name" => "ninja",
                    "position" => 1,
                    "scoreSum" => 24,
                ],
                [
                    "name" => "rockstar",
                    "position" => 2,
                    "scoreSum" => 21,
                ],
                [
                    "name" => "motherSoccer",
                    "position" => 3,
                    "scoreSum" => 15,
                ],
            ],
        ]);
        $this->redisService->set(RedisKeyService::getTable(),json_encode($userRatingListDataProvider->toArray()));
    }


    protected function tearDown(): void
    {
        $this->appFixtures->truncateTable($this->entityManager);
        $this->redisService->delete(RedisKeyService::getTable());

        $this->entityManager->close();
        $this->entityManager = null;

        parent::tearDown();
    }

    public function getData()
    {
        return [
            [
                'method' => 'GET',
                'uri' => '/api/rating',
                'expectedCode' => 200,
                'expectedResult' => '{"success":true,"data":{"users":[{"name":"ninja","position":1,"scoreSum":24},{"name":"rockstar","position":2,"scoreSum":21},{"name":"motherSoccer","position":3,"scoreSum":15}]}}',
            ],
        ];
    }

    /**
     * @dataProvider getData
     */
    public function testUrl(string $method, string $uri, int $expectedCode, string $expectedResult)
    {
        $userRepository = static::$container->get(UserRepository::class);
        $customerUser = $userRepository->find(2);

        $header = [
            'CONTENT_TYPE' => 'application/json',
            'Authorization' => $customerUser->getToken(),
        ];


        $this->client->request($method, $uri, [], [], $header);

        self::assertResponseStatusCodeSame($expectedCode);

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $responseRequest = $response->getContent();

        self::assertSame($expectedResult, $responseRequest);
    }
}
