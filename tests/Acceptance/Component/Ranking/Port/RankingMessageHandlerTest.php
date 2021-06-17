<?php declare(strict_types=1);

namespace App\Tests\Acceptance\Component\Ranking\Port;

use App\DataTransferObject\RankingAllEventDataProvider;
use App\DataTransferObject\RankingInfoEventDataProvider;
use App\DataTransferObject\UserInfoEventDataProvider;
use App\Service\Redis\RedisService;
use App\Service\RedisKey\RedisKeyService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RankingMessageHandlerTest extends KernelTestCase
{
    private RedisService $redisService;
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

        $this->messageBus = self::$container->get(MessageBusInterface::class);

        $this->redisService = self::$container->get(RedisService::class);
    }

    protected function tearDown(): void
    {
        $this->entityManager->getConnection()->executeStatement('TRUNCATE messenger_messages');

        $this->redisService->deleteAll();


        $this->entityManager->close();
        $this->entityManager = null;

        parent::tearDown();
    }


    public function testExecute()
    {
        $json = file_get_contents(__DIR__ . '/ranking.json');
        $data = \Safe\json_decode($json, true);

        $rankingAllEventDataProvider = new RankingAllEventDataProvider();
        $rankingAllEventDataProvider->fromArray($data);
        $this->messageBus->dispatch($rankingAllEventDataProvider);
        $this->callMessengerConsumeCommand();

        $table = \Safe\json_decode($this->redisService->get(RedisKeyService::getTable()), true);

        $rankingInfoEventDataProviderForTable = new RankingInfoEventDataProvider();
        $rankingInfoEventDataProviderForTable->fromArray($table);

        $users = $rankingInfoEventDataProviderForTable->getUsers();

        self::assertCount(2, $users);

        self::assertSame('rockstar', $users[0]->getName());
        self::assertSame(1, $users[0]->getPosition());
        self::assertSame(15, $users[0]->getScoreSum());
        self::assertEmpty($users[0]->getTips());

        self::assertSame('ninja', $users[1]->getName());
        self::assertSame(2, $users[1]->getPosition());
        self::assertSame(14, $users[1]->getScoreSum());
        self::assertEmpty($users[1]->getTips());

        $user = \Safe\json_decode($this->redisService->get(RedisKeyService::getUserTips('ninja')), true);

        $userInfoEventDataProvider = new UserInfoEventDataProvider();
        $userInfoEventDataProvider->fromArray($user);

        self::assertSame('ninja', $userInfoEventDataProvider->getName());
        self::assertSame(2, $userInfoEventDataProvider->getPosition());
        self::assertSame(14, $userInfoEventDataProvider->getScoreSum());
        self::assertCount(8, $userInfoEventDataProvider->getTips());

        $expecedTips = $rankingAllEventDataProvider->getData()->getUsers()[1]->getTips();
        foreach ($userInfoEventDataProvider->getTips() as $key =>  $checkTip) {
            self::assertSame($expecedTips[$key]->getMatchId(), $checkTip->getMatchId());
            self::assertSame($expecedTips[$key]->getScore(), $checkTip->getScore());
            self::assertSame($expecedTips[$key]->getUser(), $checkTip->getUser());
            self::assertSame($expecedTips[$key]->getTipTeam1(), $checkTip->getTipTeam1());
            self::assertSame($expecedTips[$key]->getTipTeam2(), $checkTip->getTipTeam2());
            self::assertSame($expecedTips[$key]->getScoreTeam1(), $checkTip->getScoreTeam1());
            self::assertSame($expecedTips[$key]->getScoreTeam2(), $checkTip->getScoreTeam2());
            self::assertSame($expecedTips[$key]->getTeam1(), $checkTip->getTeam1());
            self::assertSame($expecedTips[$key]->getTeam2(), $checkTip->getTeam2());
        }

        $user = \Safe\json_decode($this->redisService->get(RedisKeyService::getUserTips('rockstar')), true);

        $userInfoEventDataProvider = new UserInfoEventDataProvider();
        $userInfoEventDataProvider->fromArray($user);

        self::assertSame('rockstar', $userInfoEventDataProvider->getName());
        self::assertSame(1, $userInfoEventDataProvider->getPosition());
        self::assertSame(15, $userInfoEventDataProvider->getScoreSum());
        self::assertCount(11, $userInfoEventDataProvider->getTips());

        $expecedTips = $rankingAllEventDataProvider->getData()->getUsers()[0]->getTips();
        foreach ($userInfoEventDataProvider->getTips() as $key =>  $checkTip) {
            self::assertSame($expecedTips[$key]->getMatchId(), $checkTip->getMatchId());
            self::assertSame($expecedTips[$key]->getScore(), $checkTip->getScore());
            self::assertSame($expecedTips[$key]->getUser(), $checkTip->getUser());
            self::assertSame($expecedTips[$key]->getTipTeam1(), $checkTip->getTipTeam1());
            self::assertSame($expecedTips[$key]->getTipTeam2(), $checkTip->getTipTeam2());
            self::assertSame($expecedTips[$key]->getScoreTeam1(), $checkTip->getScoreTeam1());
            self::assertSame($expecedTips[$key]->getScoreTeam2(), $checkTip->getScoreTeam2());
            self::assertSame($expecedTips[$key]->getTeam1(), $checkTip->getTeam1());
            self::assertSame($expecedTips[$key]->getTeam2(), $checkTip->getTeam2());
        }
    }

    private function callMessengerConsumeCommand(): void
    {
        $application = new Application($this->appKernel);

        $command = $application->find('messenger:consume');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'receivers' => ['calculation.to.app'], '--limit' => '1', '--time-limit' => 1,
        ]);

        $output = $commandTester->getDisplay();
    }
}
