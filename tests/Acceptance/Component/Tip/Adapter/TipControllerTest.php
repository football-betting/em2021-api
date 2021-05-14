<?php declare(strict_types=1);

namespace App\Tests\Acceptance\Component\Tip\Adapter;

use App\Component\Ksb\Repository\ProductFilterExcludeWriteManager;
use App\Repository\LoggerRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Component\Tip\Adapter\TipController
 */
class TipControllerTest extends WebTestCase
{
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
    }


    protected function tearDown(): void
    {
        $this->entityManager->getConnection()->executeUpdate('DELETE FROM logger');

        $this->entityManager->close();
        $this->entityManager = null;

        parent::tearDown();
    }

    public function testSendTip()
    {
        $userRepository = static::$container->get(UserRepository::class);
        $customerUser = $userRepository->find(1);
        $this->client->loginUser($customerUser);

        $filtersInfo = [
            "matchId" => "2021-06-15:2100:DE-FR",
            "tipDatetime" => "2020-05-30 14:36",
            "tipTeam1" => 2,
            "tipTeam2" => 3,
        ];

        $this->client->request(
            'POST',
            '/tip/send',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($filtersInfo)
        );

        self::assertResponseStatusCodeSame(200);

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);

        self::assertTrue($contents['success']);

        $logger = $this->loggerRepository->findAll();
        self::assertCount(1, $logger);

//        $logInfo = json_$logger[0]
//        self::assertSame(, $logger[0]);
    }
}
