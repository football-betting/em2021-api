<?php declare(strict_types=1);

namespace App\Tests\Api\Component\Tip\Infrastructure;

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

    public function getData()
    {
        return [
            [
                'method' => 'POST',
                'uri' => '/api/tip/send',
                'content' => '{"matchId": "2099-06-15:2100:DE-FR", "tipTeam1": 2, "tipTeam2": 3}',
                'expectedCode' => 200,
                'expectedResult' => ''
            ]
        ];
    }

    /**
     * @dataProvider getData
     *
     */
    public function testSendTip($method, $uri, $content, $expectedCode, $expectedResult)
    {
        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        $customerUser = $userRepository->find(2);


        $auth = [
            'CONTENT_TYPE' => 'application/json',
            'Authorization' => $customerUser->getToken()
        ];
        $this->client->request($method, $uri, [],[], $auth, $content);

        self::assertResponseStatusCodeSame($expectedCode);

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $responseRequest = $response->getContent();

        self::assertSame('{"success":true}',$responseRequest);
    }
}
