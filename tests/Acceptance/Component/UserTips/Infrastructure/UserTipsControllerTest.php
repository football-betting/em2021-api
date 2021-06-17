<?php declare(strict_types=1);

namespace App\Tests\Acceptance\Component\UserTips\Infrastructure;

use App\DataFixtures\AppFixtures;
use App\DataTransferObject\RankingAllEventDataProvider;
use App\DataTransferObject\UserInfoEventDataProvider;
use App\Repository\UserRepository;
use App\Service\Redis\RedisService;
use App\Service\RedisKey\RedisKeyService;
use App\Tests\Fixtures\UserTips;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTipsControllerTest extends WebTestCase
{
    private RedisService $redisService;
    private ?EntityManager $entityManager;
    private KernelBrowser $client;

    /**
     * @var \App\DataTransferObject\UserInfoEventDataProvider[]
     */
    private array $demoData = [];

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

        $json = file_get_contents(__DIR__ . '/userTips.json');
        $data = \Safe\json_decode($json, true);

        $rankingAllEventDataProvider = new RankingAllEventDataProvider();
        $rankingAllEventDataProvider->fromArray($data);

        foreach ($rankingAllEventDataProvider->getData()->getUsers() as $user) {

            $this->demoData[$user->getName()] = $user;

            $this->redisService->set(
                RedisKeyService::getUserTips($user->getName()),
                json_encode($user->toArray())
            );
        }
    }


    protected function tearDown(): void
    {
        static::$container->get(AppFixtures::class)->truncateTable($this->entityManager);

        $this->demoData = [];
        $this->redisService->deleteAll();

        $this->entityManager->close();
        $this->entityManager = null;

        parent::tearDown();
    }

    public function testUserPastTip()
    {
        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        $customerUser = $userRepository->find(2);


        $this->client->request(
            'GET',
            '/api/user_tip/past/' . UserTips::USER,
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
        self::assertArrayHasKey('data', $contents);

        $data = $contents['data'];

        $checkUserInfoEventDataProvider = new UserInfoEventDataProvider();
        $checkUserInfoEventDataProvider->fromArray($data);

        $expectedData = $this->demoData[UserTips::USER];
        self::assertSame($expectedData->getName(), $checkUserInfoEventDataProvider->getName());
        self::assertSame($expectedData->getScoreSum(), $checkUserInfoEventDataProvider->getScoreSum());
        self::assertSame($expectedData->getPosition(), $checkUserInfoEventDataProvider->getPosition());
        self::assertSame($expectedData->getSumScoreDiff(), $checkUserInfoEventDataProvider->getSumScoreDiff());
        self::assertSame($expectedData->getSumTeam(), $checkUserInfoEventDataProvider->getSumTeam());
        self::assertSame($expectedData->getSumWinExact(), $checkUserInfoEventDataProvider->getSumWinExact());

    }

    public function testUserFutureTip()
    {
        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        $customerUser = $userRepository->find(1);


        $this->client->request(
            'GET',
            '/api/user_tip/future',
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
        self::assertArrayHasKey('data', $contents);

        $data = $contents['data'];

        self::assertArrayHasKey('tips', $data);

        $tips = $data['tips'];
        self::assertCount(2, $tips);

        self::assertCount(9, $tips[0]);
        self::assertCount(9, $tips[1]);

        $tip = $tips[0];
        self::assertSame(4, $tip['tipTeam1']);
        self::assertSame(5, $tip['tipTeam2']);
        self::assertNull($tip['scoreTeam1']);
        self::assertNull($tip['scoreTeam2']);
        self::assertSame('CZ', $tip['team1']);
        self::assertSame('NL', $tip['team2']);
        self::assertNull($tip['score']);

        $tip = $tips[1];
        self::assertSame('RU', $tip['team1']);
        self::assertSame('EN', $tip['team2']);
        self::assertSame(4, $tip['tipTeam1']);
        self::assertSame(2, $tip['tipTeam2']);
        self::assertNull($tip['score']);
        self::assertNull($tip['scoreTeam1']);
        self::assertNull($tip['scoreTeam2']);
    }

    public function testUserFutureTipWhenNoInfoInUser()
    {
        $this->redisService->delete(
            RedisKeyService::getUserTips(UserTips::USER),
        );


        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        $customerUser = $userRepository->find(1);


        $this->client->request(
            'GET',
            '/api/user_tip/future',
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
        self::assertArrayHasKey('data', $contents);

        $data = $contents['data'];

        self::assertArrayHasKey('tips', $data);

        $tips = $data['tips'];
        self::assertCount(2, $tips);

        self::assertCount(9, $tips[0]);
        self::assertCount(9, $tips[1]);

        $tip = $tips[0];
        self::assertSame(4, $tip['tipTeam1']);
        self::assertSame(5, $tip['tipTeam2']);
        self::assertNull($tip['scoreTeam1']);
        self::assertNull($tip['scoreTeam2']);
        self::assertSame('CZ', $tip['team1']);
        self::assertSame('NL', $tip['team2']);
        self::assertNull($tip['score']);

        $tip = $tips[1];
        self::assertSame('RU', $tip['team1']);
        self::assertSame('EN', $tip['team2']);
        self::assertSame(4, $tip['tipTeam1']);
        self::assertSame(2, $tip['tipTeam2']);
        self::assertNull($tip['score']);
        self::assertNull($tip['scoreTeam1']);
        self::assertNull($tip['scoreTeam2']);
    }

    public function testNotFoundUserPastTip()
    {
        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        $customerUser = $userRepository->find(2);


        $this->client->request(
            'GET',
            '/api/user_tip/past/not_found_user',
            [],
            [],
            [
                'Authorization' => $customerUser->getToken(),
            ]
        );

        self::assertResponseStatusCodeSame(500);

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);

        self::assertSame('Syntax error', $contents['message']);
        self::assertFalse($contents['success']);
    }
}
