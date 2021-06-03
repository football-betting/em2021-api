<?php declare(strict_types=1);

namespace App\Tests\Acceptance\Component\UserTips\Infrastructure;

use App\DataFixtures\AppFixtures;
use App\DataTransferObject\TipEventDataProvider;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTipsControllerTest extends WebTestCase
{
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


        $this->client->request(
            'GET',
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

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);

        self::assertTrue($contents['success']);

        $logger = $this->loggerRepository->findAll();
        self::assertCount(1, $logger);

        $loggerEntity = $logger[0];
        self::assertSame(TipEventDataProvider::class, $loggerEntity->getClass());

        $tipEventDataProvider = new TipEventDataProvider();
        $tipEventDataProvider->fromArray($loggerEntity->getData());
        self::assertSame($filtersInfo['matchId'], $tipEventDataProvider->getMatchId());
        self::assertSame($filtersInfo['tipDatetime'], $tipEventDataProvider->getTipDatetime());
        self::assertSame($filtersInfo['tipTeam1'], $tipEventDataProvider->getTipTeam1());
        self::assertSame($filtersInfo['tipTeam2'], $tipEventDataProvider->getTipTeam2());
        self::assertSame($customerUser->getUsername(), $tipEventDataProvider->getUser());

        $sql = "SELECT * FROM messenger_messages";
        $stmt = $this->entityManager->getConnection()->prepare($sql);
        $resultList = $stmt->executeQuery()->fetchAllAssociative();

        self::assertCount(1, $resultList);

        $result = $resultList[0];

        self::assertSame('tip.user',$result['queue_name']);
        $body = json_decode($result['body'], true);
        self::assertSame($result['queue_name'], $body['event']);

        $tipEventDataProvider = new TipEventDataProvider();
        $tipEventDataProvider->fromArray($body['data']);

        self::assertSame($filtersInfo['matchId'], $tipEventDataProvider->getMatchId());
        self::assertSame($filtersInfo['tipDatetime'], $tipEventDataProvider->getTipDatetime());
        self::assertSame($filtersInfo['tipTeam1'], $tipEventDataProvider->getTipTeam1());
        self::assertSame($filtersInfo['tipTeam2'], $tipEventDataProvider->getTipTeam2());
        self::assertSame($customerUser->getUsername(), $tipEventDataProvider->getUser());
    }
}
