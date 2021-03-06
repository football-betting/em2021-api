<?php declare(strict_types=1);

namespace App\Tests\Acceptance\Component\Tip\Infrastructure;

use App\DataFixtures\AppFixtures;
use App\DataTransferObject\TipEventDataProvider;
use App\Repository\LoggerRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class TipControllerTest extends WebTestCase
{
    private ?EntityManager $entityManager;
    private LoggerRepository $loggerRepository;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        $this->entityManager = self::$container
            ->get('doctrine')
            ->getManager();

        $this->loggerRepository = static::$container->get(LoggerRepository::class);
        static::$container->get(AppFixtures::class)->load($this->entityManager);
    }


    protected function tearDown(): void
    {
        static::$container->get(AppFixtures::class)->truncateTable($this->entityManager);
        $this->entityManager->getConnection()->executeStatement('TRUNCATE messenger_messages');
        $this->entityManager->getConnection()->executeStatement('TRUNCATE logger');

        $this->entityManager->close();
        $this->entityManager = null;

        parent::tearDown();
    }

    public function testSendTip()
    {
        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        $customerUser = $userRepository->find(1);

        $filtersInfo = [
            "matchId" => "2099-06-15:2100:DE-FR",
            "tipDatetime" => "2020-05-30 14:36",
            "tipTeam1" => 2,
            "tipTeam2" => 3,
        ];

        $this->client->request(
            'POST',
            '/api/tip/send',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'Authorization' => $customerUser->getToken()
            ],
            json_encode($filtersInfo)
        );

        self::assertResponseStatusCodeSame(200);

        $logger = $this->loggerRepository->findAll();
        self::assertCount(1, $logger);

        $loggerEntity = $logger[0];
        self::assertSame(TipEventDataProvider::class, $loggerEntity->getClass());

        $tipEventDataProvider = new TipEventDataProvider();

        $tipEventDataProvider->fromArray($loggerEntity->getData());
        self::assertSame($filtersInfo['matchId'], $tipEventDataProvider->getMatchId());
        self::assertSame((new \DateTime())->format('Y-m-d H:i'), $tipEventDataProvider->getTipDatetime());
        self::assertSame($filtersInfo['tipTeam1'], $tipEventDataProvider->getTipTeam1());
        self::assertSame($filtersInfo['tipTeam2'], $tipEventDataProvider->getTipTeam2());
        self::assertSame($customerUser->getUsername(), $tipEventDataProvider->getUser());

        $sql = "SELECT * FROM messenger_messages";
        $stmt = $this->entityManager->getConnection()->prepare($sql);
        $resultList = $stmt->executeQuery()->fetchAllAssociative();

        self::assertCount(1, $resultList);

        $result = $resultList[0];

        self::assertSame('app.to.tip',$result['queue_name']);
        $body = json_decode($result['body'], true);
        self::assertSame($result['queue_name'], $body['event']);

        $tipEventDataProvider = new TipEventDataProvider();
        $tipEventDataProvider->fromArray($body['data']);

        self::assertSame($filtersInfo['matchId'], $tipEventDataProvider->getMatchId());
        self::assertSame((new \DateTime())->format('Y-m-d H:i'), $tipEventDataProvider->getTipDatetime());
        self::assertSame($filtersInfo['tipTeam1'], $tipEventDataProvider->getTipTeam1());
        self::assertSame($filtersInfo['tipTeam2'], $tipEventDataProvider->getTipTeam2());
        self::assertSame($customerUser->getUsername(), $tipEventDataProvider->getUser());
    }

    public function testSendNoAllowedTip()
    {
        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        $customerUser = $userRepository->find(1);

        $filtersInfo = [
            "matchId" => "2000-06-15:2100:DE-FR",
            "tipTeam1" => 2,
            "tipTeam2" => 3,
        ];

        $this->client->request(
            'POST',
            '/api/tip/send',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'Authorization' => $customerUser->getToken()
            ],
            json_encode($filtersInfo)
        );

        self::assertResponseStatusCodeSame(422);

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);

        self::assertFalse($contents['success']);
        self::assertSame('For games in the past you can not type', $contents['message']);

        $logger = $this->loggerRepository->findAll();
        self::assertCount(0, $logger);


        $sql = "SELECT * FROM messenger_messages";
        $stmt = $this->entityManager->getConnection()->prepare($sql);
        $resultList = $stmt->executeQuery()->fetchAllAssociative();

        self::assertCount(0, $resultList);
    }

    public function testSendForErrorTip()
    {
        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        $customerUser = $userRepository->find(1);

        $filtersInfo = [
            "matchId" => "2000-06-15:2100:DE-FR",
            "tipTeam2" => 3,
        ];

        $this->client->request(
            'POST',
            '/api/tip/send',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'Authorization' => $customerUser->getToken()
            ],
            json_encode($filtersInfo)
        );

        self::assertResponseStatusCodeSame(422);

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);

        self::assertFalse($contents['success']);
        self::assertSame('The required properties (tipTeam1) are missing', $contents['message'][0]);

        $logger = $this->loggerRepository->findAll();
        self::assertCount(0, $logger);


        $sql = "SELECT * FROM messenger_messages";
        $stmt = $this->entityManager->getConnection()->prepare($sql);
        $resultList = $stmt->executeQuery()->fetchAllAssociative();

        self::assertCount(0, $resultList);
    }
}
