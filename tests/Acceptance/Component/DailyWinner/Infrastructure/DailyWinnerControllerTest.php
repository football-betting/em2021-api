<?php declare(strict_types=1);

namespace App\Tests\Acceptance\Component\DailyWinner\Infrastructure;

use App\DataFixtures\AppFixtures;
use App\DataTransferObject\DailyWinnerListDataProvider;
use App\Repository\UserRepository;
use App\Service\Redis\RedisService;
use App\Service\RedisKey\RedisKeyService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DailyWinnerControllerTest extends WebTestCase
{
    private const DATA = [
        'data' =>
            [
                [
                    'users' =>
                        [
                            'ninja',
                            'rockstar',
                        ],
                    'points' => 6,
                    'matchDate' => '2021-06-20',
                ],
                [
                    'users' =>
                        [
                            'rockstar',
                        ],
                    'points' => 4,
                    'matchDate' => '2021-06-21',
                ],
                [
                    'users' =>
                        [
                            'motherSoccer',
                        ],
                    'points' => 5,
                    'matchDate' => '2021-06-22',
                ],
            ],
    ];


    private RedisService $redisService;
    private ?EntityManager $entityManager;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        $this->entityManager = self::$container
            ->get('doctrine')
            ->getManager();

        static::$container->get(AppFixtures::class)->load($this->entityManager);

        /** @var RedisService redisService */
        $this->redisService = static::$container->get(RedisService::class);

        $this->redisService->set(
            RedisKeyService::getDailyWinner(),
            json_encode(self::DATA)
        );
    }


    protected function tearDown(): void
    {
        static::$container->get(AppFixtures::class)->truncateTable($this->entityManager);
        $this->redisService->deleteAll();

        $this->entityManager->close();
        $this->entityManager = null;

        parent::tearDown();
    }

    public function testDailyWinners()
    {
        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        $customerUser = $userRepository->find(2);

        $this->client->request(
            'GET',
            '/api/daily-winners',
            [],
            [],
            [
                'Authorization' => $customerUser->getToken(),
            ]
        );

        self::assertResponseStatusCodeSame(200);

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);

        self::assertTrue($contents['success']);
        self::assertCount(3, $contents['data']);

        $checkData = $contents['data'][0];
        $expectedData = self::DATA['data'][0];

        self::assertSame($expectedData['users'],$checkData['users']);
        self::assertSame($expectedData['points'],$checkData['points']);
        self::assertSame($expectedData['matchDate'],$checkData['matchDate']);

        $checkData = $contents['data'][1];
        $expectedData = self::DATA['data'][1];

        self::assertSame($expectedData['users'],$checkData['users']);
        self::assertSame($expectedData['points'],$checkData['points']);
        self::assertSame($expectedData['matchDate'],$checkData['matchDate']);

        $checkData = $contents['data'][2];
        $expectedData = self::DATA['data'][2];

        self::assertSame($expectedData['users'],$checkData['users']);
        self::assertSame($expectedData['points'],$checkData['points']);
        self::assertSame($expectedData['matchDate'],$checkData['matchDate']);
    }
}
