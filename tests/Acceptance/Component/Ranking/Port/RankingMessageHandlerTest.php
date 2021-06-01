<?php declare(strict_types=1);

namespace App\Tests\Acceptance\Component\Ranking\Port;

use App\DataTransferObject\RankingAllEventDataProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RankingMessageHandlerTest extends KernelTestCase
{

    private ?EntityManager $entityManager;
    private MessageBusInterface $messageBus;
    private KernelInterface $appKernel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->appKernel = self::bootKernel();

//        $this->appKernel = static::createKernel();
        $this->entityManager = self::$container
            ->get('doctrine')
            ->getManager();

        $this->messageBus = static::$container->get(MessageBusInterface::class);
    }

    protected function tearDown(): void
    {
        $this->entityManager->getConnection()->executeStatement('TRUNCATE messenger_messages');

        $this->entityManager->close();
        $this->entityManager = null;

        parent::tearDown();
    }


    public function testExecute()
    {
        $data = [
            "data" => [
                "games" => [
                    [
                        "matchId" => "2020-06-16:2100:FR-DE",
                        "team1" => "FR",
                        "team2" => "DE",
                        "matchDatetime" => "2020-06-16 21:00",
                        "scoreTeam1" => 1,
                        "scoreTeam2" => 4,
                    ],
                    [
                        "matchId" => "2020-06-20:1800:PT-DE",
                        "team1" => "PT",
                        "team2" => "DE",
                        "matchDatetime" => "2020-06-20 18:00",
                        "scoreTeam1" => null,
                        "scoreTeam2" => null,
                    ],
                    [
                        "matchId" => "2020-07-20:1800:PL-RU",
                        "team1" => "PL",
                        "team2" => "RU",
                        "matchDatetime" => "2020-07-20 18:00",
                        "scoreTeam1" => null,
                        "scoreTeam2" => null,
                    ],
                ],
                "users" => [
                    [
                        "name" => "ninja",
                        "position" => 1,
                        "scoreSum" => 24,
                        "tips" => [
                            [
                                "matchId" => "2020-06-16:2100:FR-DE",
                                "score" => 4,
                                "tipTeam1" => 2,
                                "tipTeam2" => 3,
                            ],
                            [
                                "matchId" => "2020-06-20:1800:PT-DE",
                                "score" => null,
                                "tipTeam1" => 4,
                                "tipTeam2" => 2,
                            ],
                        ],
                    ],
                    [
                        "name" => "rockstar",
                        "position" => 2,
                        "scoreSum" => 21,
                        "tips" => [
                            [
                                "matchId" => "2020-06-16:2100:FR-DE",
                                "score" => 2,
                                "tipTeam1" => 1,
                                "tipTeam2" => 1,
                            ],
                            [
                                "matchId" => "2020-06-20:1800:PT-DE",
                                "score" => null,
                                "tipTeam1" => 4,
                                "tipTeam2" => 2,
                            ],
                            [
                                "matchId" => "2020-07-20:1800:PL-RU",
                                "score" => null,
                                "tipTeam1" => 1,
                                "tipTeam2" => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $rankingAllEventDataProvider = new RankingAllEventDataProvider();
        $rankingAllEventDataProvider->fromArray($data);
        $this->messageBus->dispatch($rankingAllEventDataProvider);
        $this->callMessengerConsumeCommand();

    }

    private function callMessengerConsumeCommand(): void
    {
        $application = new Application($this->appKernel);

        $command = $application->find('messenger:consume');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'receivers' => ['ranking.all'], '--limit' => '1', '--time-limit' => 1,
        ]);

        $output = $commandTester->getDisplay();
    }
}
