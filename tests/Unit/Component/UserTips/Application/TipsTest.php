<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\UserTips\Application;

use App\Component\UserTips\Application\Tips;
use App\Service\Redis\RedisServiceInterface;
use App\Service\RedisKey\RedisKeyService;
use App\Tests\Fixtures\RedisDummy;
use App\Tests\Fixtures\UserTips;
use PHPUnit\Framework\TestCase;

class TipsTest extends TestCase
{
    private array $dummyData;

    private array $expectedDate;

    protected function setUp(): void
    {
        parent::setUp();

        $userTips = new UserTips();
        $this->dummyData = $userTips->getDummyData();
        $this->expectedDate = $userTips->expectedDate;
    }

    public function testGetUserTips()
    {
        $tips = new Tips($this->getRedisWithDummyData());
        $userInfoDataProvider = $tips->getUserTips(UserTips::USER);

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
        $userInfoDataProvider = $tips->getFutureUserTips(UserTips::USER);

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
        $userInfoDataProvider = $tips->getPastUserTips(UserTips::USER);

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

        $redisDummy->set(RedisKeyService::getUserTips(UserTips::USER), json_encode($this->dummyData));
        $redisDummy->set(RedisKeyService::getUserTips('empty_data'), json_encode([]));
        $redisDummy->set(RedisKeyService::getUserTips('empty_error'), '');

        return $redisDummy;
    }
}
