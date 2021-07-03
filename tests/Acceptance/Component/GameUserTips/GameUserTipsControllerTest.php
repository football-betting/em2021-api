<?php


namespace App\Tests\Acceptance\Component\GameUserTips;


use App\DataFixtures\AppFixtures;
use App\DataTransferObject\RankingAllEventDataProvider;
use App\DataTransferObject\RankingInfoEventDataProvider;
use App\DataTransferObject\UserInfoEventDataProvider;
use App\Repository\UserRepository;
use App\Service\Redis\RedisService;
use App\Service\RedisKey\RedisKeyService;
use App\Tests\Fixtures\UserTips;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class GameUserTipsControllerTest extends WebTestCase
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

        $json = file_get_contents(__DIR__ . '/gameUserTips.json');
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


    public function testPastGameUsersTip()
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



}