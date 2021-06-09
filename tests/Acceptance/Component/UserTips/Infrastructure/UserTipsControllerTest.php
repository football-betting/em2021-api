<?php declare(strict_types=1);

namespace App\Tests\Acceptance\Component\UserTips\Infrastructure;

use App\DataFixtures\AppFixtures;
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

        $userTips = new UserTips();
        $this->redisService->set(
            RedisKeyService::getUserTips(UserTips::USER),
            json_encode($userTips->getDummyData())
        );
    }


    protected function tearDown(): void
    {
        static::$container->get(AppFixtures::class)->truncateTable($this->entityManager);

        $this->redisService->delete(
            RedisKeyService::getUserTips(UserTips::USER),
        );

        $this->entityManager->close();
        $this->entityManager = null;

        parent::tearDown();
    }

    public function testUserTipAll()
    {
        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        $customerUser = $userRepository->find(1);


        $this->client->request(
            'GET',
            '/api/user_tip/all',
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
        self::assertCount(5, $tips);

        self::assertCount(9, $tips[0]);
        self::assertCount(9, $tips[1]);
        self::assertCount(9, $tips[2]);
        self::assertCount(9, $tips[3]);
        self::assertCount(9, $tips[4]);

        $tip = $tips[0];
        self::assertSame('2000-06-16:2100:FR-DE', $tip['matchId']);
        self::assertSame('2000-06-16 21:00', $tip['matchDatetime']);
        self::assertSame(2, $tip['tipTeam1']);
        self::assertSame(3, $tip['tipTeam2']);
        self::assertSame(1, $tip['scoreTeam1']);
        self::assertSame(4, $tip['scoreTeam2']);
        self::assertSame('FR', $tip['team1']);
        self::assertSame('DE', $tip['team2']);
        self::assertSame(2, $tip['score']);

        $tip = $tips[2];
        self::assertNull($tip['tipTeam1']);
        self::assertNull($tip['tipTeam2']);
        self::assertNull($tip['scoreTeam1']);
        self::assertNull($tip['scoreTeam2']);
        self::assertSame('PR', $tip['team1']);
        self::assertSame('AU', $tip['team2']);
        self::assertNull($tip['score']);
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
                'Authorization' => $customerUser->getToken()
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
        self::assertCount(3, $tips);

        self::assertCount(9, $tips[0]);
        self::assertCount(9, $tips[1]);
        self::assertCount(9, $tips[2]);

        $tip = $tips[0];
        self::assertSame('2000-06-16:2100:FR-DE', $tip['matchId']);
        self::assertSame('2000-06-16 21:00', $tip['matchDatetime']);
        self::assertSame(2, $tip['tipTeam1']);
        self::assertSame(3, $tip['tipTeam2']);
        self::assertSame(1, $tip['scoreTeam1']);
        self::assertSame(4, $tip['scoreTeam2']);
        self::assertSame('FR', $tip['team1']);
        self::assertSame('DE', $tip['team2']);
        self::assertSame(2, $tip['score']);

        $tip = $tips[2];
        self::assertNull($tip['tipTeam1']);
        self::assertNull($tip['tipTeam2']);
        self::assertNull($tip['score']);
        self::assertNull($tip['scoreTeam1']);
        self::assertNull($tip['scoreTeam2']);
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
