<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\UserTips\Application;

use App\Component\UserTips\Application\Tips;
use App\DataTransferObject\UserInfoDataProvider;
use App\Service\Redis\RedisServiceInterface;
use App\Service\RedisKey\RedisKeyService;
use App\Tests\Fixtures\RedisDummy;
use PHPUnit\Framework\TestCase;

class TipsTest extends TestCase
{
    private $expectedDate = [];

    public function testGetUserTips()
    {
        $tips = new Tips($this->getRedisWithDummyData());
        $userInfoDataProvider = $tips->getUserTips(self::USER);

        self::assertSame('ninja', $userInfoDataProvider->getName());
        self::assertSame(1, $userInfoDataProvider->getPosition());
        self::assertSame(24, $userInfoDataProvider->getScoreSum());

        $tipInfoDataProviderList = $userInfoDataProvider->getTips();
        self::assertCount(5, $tipInfoDataProviderList);

        $tipInfoDataProvider = $tipInfoDataProviderList[0];

        self::assertSame('2000-06-16:2100:FR-DE', $tipInfoDataProvider->getMatchId());
        self::assertSame('2000-06-16 21:00', $tipInfoDataProvider->getMatchDatetime());
        self::assertSame(2, $tipInfoDataProvider->getTipTeam1());
        self::assertSame(3, $tipInfoDataProvider->getTipTeam2());
        self::assertSame(1, $tipInfoDataProvider->getScoreTeam1());
        self::assertSame(4, $tipInfoDataProvider->getScoreTeam2());
        self::assertSame('FR', $tipInfoDataProvider->getTeam1());
        self::assertSame('DE', $tipInfoDataProvider->getTeam2());
        self::assertSame(2, $tipInfoDataProvider->getScore());

        $tipInfoDataProvider = $tipInfoDataProviderList[1];

        self::assertSame($this->expectedDate[1]['matchId'], $tipInfoDataProvider->getMatchId());
        self::assertSame($this->expectedDate[1]['matchDatetime'], $tipInfoDataProvider->getMatchDatetime());
        self::assertSame(1, $tipInfoDataProvider->getTipTeam1());
        self::assertSame(0, $tipInfoDataProvider->getTipTeam2());
        self::assertSame(1, $tipInfoDataProvider->getScoreTeam1());
        self::assertSame(4, $tipInfoDataProvider->getScoreTeam2());
        self::assertSame('IT', $tipInfoDataProvider->getTeam1());
        self::assertSame('SP', $tipInfoDataProvider->getTeam2());
        self::assertSame(0, $tipInfoDataProvider->getScore());

        $tipInfoDataProvider = $tipInfoDataProviderList[2];

        self::assertSame($this->expectedDate[2]['matchId'], $tipInfoDataProvider->getMatchId());
        self::assertSame($this->expectedDate[2]['matchDatetime'], $tipInfoDataProvider->getMatchDatetime());
        self::assertSame(2, $tipInfoDataProvider->getTipTeam1());
        self::assertSame(3, $tipInfoDataProvider->getTipTeam2());
        self::assertSame('PR', $tipInfoDataProvider->getTeam1());
        self::assertSame('AU', $tipInfoDataProvider->getTeam2());

        self::assertNull($tipInfoDataProvider->getScore());
        self::assertNull($tipInfoDataProvider->getScoreTeam1());
        self::assertNull($tipInfoDataProvider->getScoreTeam2());

        $tipInfoDataProvider = $tipInfoDataProviderList[3];

        self::assertSame($this->expectedDate[3]['matchId'], $tipInfoDataProvider->getMatchId());
        self::assertSame($this->expectedDate[3]['matchDatetime'], $tipInfoDataProvider->getMatchDatetime());
        self::assertSame(4, $tipInfoDataProvider->getTipTeam1());
        self::assertSame(5, $tipInfoDataProvider->getTipTeam2());
        self::assertSame('CZ', $tipInfoDataProvider->getTeam1());
        self::assertSame('NL', $tipInfoDataProvider->getTeam2());

        self::assertNull($tipInfoDataProvider->getScore());
        self::assertNull($tipInfoDataProvider->getScoreTeam1());
        self::assertNull($tipInfoDataProvider->getScoreTeam2());

        $tipInfoDataProvider = $tipInfoDataProviderList[4];

        self::assertSame('2999-06-20:1800:RU-EN', $tipInfoDataProvider->getMatchId());
        self::assertSame('2999-06-20 18:00', $tipInfoDataProvider->getMatchDatetime());
        self::assertSame(4, $tipInfoDataProvider->getTipTeam1());
        self::assertSame(2, $tipInfoDataProvider->getTipTeam2());
        self::assertSame('RU', $tipInfoDataProvider->getTeam1());
        self::assertSame('EN', $tipInfoDataProvider->getTeam2());

        self::assertNull($tipInfoDataProvider->getScore());
        self::assertNull($tipInfoDataProvider->getScoreTeam1());
        self::assertNull($tipInfoDataProvider->getScoreTeam2());
    }

    public function testGetFutureUserTips()
    {
        $tips = new Tips($this->getRedisWithDummyData());
        $userInfoDataProvider = $tips->getFutureUserTips(self::USER);

        self::assertSame('ninja', $userInfoDataProvider->getName());
        self::assertSame(1, $userInfoDataProvider->getPosition());
        self::assertSame(24, $userInfoDataProvider->getScoreSum());

        $tipInfoDataProviderList = $userInfoDataProvider->getTips();
        self::assertCount(2, $tipInfoDataProviderList);

        $tipInfoDataProvider = $tipInfoDataProviderList[0];

        self::assertSame($this->expectedDate[3]['matchId'], $tipInfoDataProvider->getMatchId());
        self::assertSame($this->expectedDate[3]['matchDatetime'], $tipInfoDataProvider->getMatchDatetime());
        self::assertSame(4, $tipInfoDataProvider->getTipTeam1());
        self::assertSame(5, $tipInfoDataProvider->getTipTeam2());
        self::assertSame('CZ', $tipInfoDataProvider->getTeam1());
        self::assertSame('NL', $tipInfoDataProvider->getTeam2());

        self::assertNull($tipInfoDataProvider->getScore());
        self::assertNull($tipInfoDataProvider->getScoreTeam1());
        self::assertNull($tipInfoDataProvider->getScoreTeam2());

        $tipInfoDataProvider = $tipInfoDataProviderList[1];

        self::assertSame('2999-06-20:1800:RU-EN', $tipInfoDataProvider->getMatchId());
        self::assertSame('2999-06-20 18:00', $tipInfoDataProvider->getMatchDatetime());
        self::assertSame(4, $tipInfoDataProvider->getTipTeam1());
        self::assertSame(2, $tipInfoDataProvider->getTipTeam2());
        self::assertSame('RU', $tipInfoDataProvider->getTeam1());
        self::assertSame('EN', $tipInfoDataProvider->getTeam2());

        self::assertNull($tipInfoDataProvider->getScore());
        self::assertNull($tipInfoDataProvider->getScoreTeam1());
        self::assertNull($tipInfoDataProvider->getScoreTeam2());
    }

    public function testGetPastUserTips()
    {
        $tips = new Tips($this->getRedisWithDummyData());
        $userInfoDataProvider = $tips->getPastUserTips(self::USER);

        self::assertSame('ninja', $userInfoDataProvider->getName());
        self::assertSame(1, $userInfoDataProvider->getPosition());
        self::assertSame(24, $userInfoDataProvider->getScoreSum());

        $tipInfoDataProviderList = $userInfoDataProvider->getTips();
        self::assertCount(3, $tipInfoDataProviderList);

        $tipInfoDataProvider = $tipInfoDataProviderList[0];

        self::assertSame('2000-06-16:2100:FR-DE', $tipInfoDataProvider->getMatchId());
        self::assertSame('2000-06-16 21:00', $tipInfoDataProvider->getMatchDatetime());
        self::assertSame(2, $tipInfoDataProvider->getTipTeam1());
        self::assertSame(3, $tipInfoDataProvider->getTipTeam2());
        self::assertSame(1, $tipInfoDataProvider->getScoreTeam1());
        self::assertSame(4, $tipInfoDataProvider->getScoreTeam2());
        self::assertSame('FR', $tipInfoDataProvider->getTeam1());
        self::assertSame('DE', $tipInfoDataProvider->getTeam2());
        self::assertSame(2, $tipInfoDataProvider->getScore());

        $tipInfoDataProvider = $tipInfoDataProviderList[1];

        self::assertSame($this->expectedDate[1]['matchId'], $tipInfoDataProvider->getMatchId());
        self::assertSame($this->expectedDate[1]['matchDatetime'], $tipInfoDataProvider->getMatchDatetime());
        self::assertSame(1, $tipInfoDataProvider->getTipTeam1());
        self::assertSame(0, $tipInfoDataProvider->getTipTeam2());
        self::assertSame(1, $tipInfoDataProvider->getScoreTeam1());
        self::assertSame(4, $tipInfoDataProvider->getScoreTeam2());
        self::assertSame('IT', $tipInfoDataProvider->getTeam1());
        self::assertSame('SP', $tipInfoDataProvider->getTeam2());
        self::assertSame(0, $tipInfoDataProvider->getScore());

        $tipInfoDataProvider = $tipInfoDataProviderList[2];

        self::assertSame($this->expectedDate[2]['matchId'], $tipInfoDataProvider->getMatchId());
        self::assertSame($this->expectedDate[2]['matchDatetime'], $tipInfoDataProvider->getMatchDatetime());
        self::assertSame(2, $tipInfoDataProvider->getTipTeam1());
        self::assertSame(3, $tipInfoDataProvider->getTipTeam2());
        self::assertSame('PR', $tipInfoDataProvider->getTeam1());
        self::assertSame('AU', $tipInfoDataProvider->getTeam2());

        self::assertNull($tipInfoDataProvider->getScore());
        self::assertNull($tipInfoDataProvider->getScoreTeam1());
        self::assertNull($tipInfoDataProvider->getScoreTeam2());
    }

    public function testErroWhenUserHasNoData()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Empty data');

        $tips = new Tips($this->getRedisWithDummyData());
        $tips->getUserTips('empty_data');
    }

    public function testErroWhenUserHasWrongData()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Syntax error');

        $tips = new Tips($this->getRedisWithDummyData());
        $tips->getUserTips('empty_error');
    }


    public function testErroWhenUserNotFound()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Syntax error');

        $tips = new Tips($this->getRedisWithDummyData());
        $tips->getUserTips('john_doe');
    }

    private function getRedisWithDummyData(): RedisServiceInterface
    {
        $redisDummy = new RedisDummy();

        $data = self::DATA;

        $keys = [1, 2, 3];
        foreach ($keys as $key) {
            $dataTime = new \DateTime($data['tips'][$key]['matchDatetime']);
            $data['tips'][$key]['matchId'] = $dataTime->format('Y-m-d:Hi') . $data['tips'][$key]['matchId'];
            $data['tips'][$key]['matchDatetime'] = $dataTime->format('Y-m-d H:i');

            $this->expectedDate[$key] = [
                'matchId' => $data['tips'][$key]['matchId'],
                'matchDatetime' => $data['tips'][$key]['matchDatetime'],
            ];
        }

        $redisDummy->set(RedisKeyService::getUserTips(self::USER), json_encode($data));
        $redisDummy->set(RedisKeyService::getUserTips('empty_data'), json_encode([]));
        $redisDummy->set(RedisKeyService::getUserTips('empty_error'), '');

        return $redisDummy;
    }

    private const USER = 'ninja';

    private const DATA = [
        'name' => self::USER,
        'position' => 1,
        'scoreSum' => 24,
        'tips' => [
            0 => [
                'matchId' => '2000-06-16:2100:FR-DE',
                'matchDatetime' => '2000-06-16 21:00',
                'tipTeam1' => 2,
                'tipTeam2' => 3,
                'scoreTeam1' => 1,
                'scoreTeam2' => 4,
                'team1' => 'FR',
                'team2' => 'DE',
                'score' => 2,
            ],
            1 => [
                'matchId' => ':IT-SP',
                'matchDatetime' => '-1 minute',
                'tipTeam1' => 1,
                'tipTeam2' => 0,
                'scoreTeam1' => 1,
                'scoreTeam2' => 4,
                'team1' => 'IT',
                'team2' => 'SP',
                'score' => 0,
            ],
            2 => [
                'matchId' => ':PR-AU',
                'matchDatetime' => 'now',
                'tipTeam1' => 2,
                'tipTeam2' => 3,
                'team1' => 'PR',
                'team2' => 'AU',
            ],
            3 => [
                'matchId' => ':CZ-NL',
                'matchDatetime' => '+ 1 minute',
                'tipTeam1' => 4,
                'tipTeam2' => 5,
                'team1' => 'CZ',
                'team2' => 'NL',
            ],
            4 => [
                'matchId' => '2999-06-20:1800:RU-EN',
                'matchDatetime' => '2999-06-20 18:00',
                'tipTeam1' => 4,
                'tipTeam2' => 2,
                'team1' => 'RU',
                'team2' => 'EN',
            ],
        ],
    ];

}
