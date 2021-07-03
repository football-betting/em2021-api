<?php declare(strict_types=1);

namespace App\Tests\Acceptance\Component\GameUserTips\Infrastructure;

use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use App\Service\Redis\RedisService;
use App\Service\RedisKey\RedisKeyService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameUserTipsControllerTest extends WebTestCase
{
    private const DATA = [
        'matchId' => '2000-06-16:2100:FR-DE',
        'team1' => 'FR',
        'team2' => 'DE',
        'scoreTeam1' => 1,
        'scoreTeam2' => 4,
        'usersTip' =>
            [
                [
                    'name' => 'ninja',
                    'score' => 2,
                    'tipTeam1' => 2,
                    'tipTeam2' => 3,
                ],
                [
                    'name' => 'rockstar',
                    'score' => 4,
                    'tipTeam1' => 0,
                    'tipTeam2' => 1,
                ],
                [
                    'name' => 'john_doe',
                    'score' => 0,
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
            RedisKeyService::getGameUsersTip(self::DATA['matchId']),
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

    public function testGetMatch()
    {
        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        $customerUser = $userRepository->find(2);

        $this->client->request(
            'GET',
            '/api/game_tip/past/2000-06-16:2100:FR-DE',
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

        $data = $contents['data'];

        self::assertSame(self::DATA['matchId'], $data['matchId']);
        self::assertSame(self::DATA['team1'], $data['team1']);
        self::assertSame(self::DATA['team2'], $data['team2']);
        self::assertSame(self::DATA['scoreTeam1'], $data['scoreTeam1']);
        self::assertSame(self::DATA['scoreTeam2'], $data['scoreTeam2']);

        self::assertCount(3, $data['usersTip']);

        $userTips = $data['usersTip'];

        self::assertSame(self::DATA['usersTip'][0]['name'],$userTips[0]['name']);
        self::assertSame(self::DATA['usersTip'][0]['score'],$userTips[0]['score']);
        self::assertSame(self::DATA['usersTip'][0]['tipTeam1'],$userTips[0]['tipTeam1']);
        self::assertSame(self::DATA['usersTip'][0]['tipTeam2'],$userTips[0]['tipTeam2']);

        self::assertSame(self::DATA['usersTip'][1]['name'],$userTips[1]['name']);
        self::assertSame(self::DATA['usersTip'][1]['score'],$userTips[1]['score']);
        self::assertSame(self::DATA['usersTip'][1]['tipTeam1'],$userTips[1]['tipTeam1']);
        self::assertSame(self::DATA['usersTip'][1]['tipTeam2'],$userTips[1]['tipTeam2']);

        self::assertSame(self::DATA['usersTip'][2]['name'],$userTips[2]['name']);
        self::assertSame(self::DATA['usersTip'][2]['score'],$userTips[2]['score']);
        self::assertNull($userTips[2]['tipTeam1']);
        self::assertNull($userTips[2]['tipTeam2']);
    }

    public function testGetMatchNotFound()
    {
        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        $customerUser = $userRepository->find(2);

        $this->client->request(
            'GET',
            '/api/game_tip/past/2000-06-16:2100:NOT-FOUND',
            [],
            [],
            [
                'Authorization' => $customerUser->getToken(),
            ]
        );

        self::assertResponseStatusCodeSame(404);

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);

        self::assertFalse($contents['success']);
        self::assertSame('2000-06-16:2100:NOT-FOUND no found',$contents['message']);
    }
}
