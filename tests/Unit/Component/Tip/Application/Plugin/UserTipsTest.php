<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\Tip\Application\Plugin;

use App\Component\Ranking\Domain\RedisDtoList;
use App\Component\Tip\Application\Plugin\UserTips;
use App\DataTransferObject\RankingAllEventDataProvider;
use PHPUnit\Framework\TestCase;

class UserTipsTest extends TestCase
{
    public function testUserTips()
    {
        $userPastTips = new UserTips();
        $redisDtoList = $userPastTips->get($this->getRankingAllEventDataProvider(), new RedisDtoList());

        $redisDto = $redisDtoList->getRedisDto();
        self::assertCount(2, $redisDto);

        self::assertSame('user:ninja:tips', $redisDto[0]->getKey());

        /** @var \App\DataTransferObject\UserInfoDataProvider $info */
        $info = $redisDto[0]->getData();
        self::assertSame('ninja', $info->getName());
        self::assertSame(1, $info->getPosition());
        self::assertSame(24, $info->getScoreSum());

        $tips = $info->getTips();
        self::assertCount(2, $tips);

        self::assertSame('2000-06-16:2100:FR-DE', $tips[0]->getMatchId());
        self::assertSame('2000-06-16 21:00', $tips[0]->getMatchDatetime());
        self::assertSame(2, $tips[0]->getTipTeam1());
        self::assertSame(3, $tips[0]->getTipTeam2());
        self::assertSame(1, $tips[0]->getScoreTeam1());
        self::assertSame(4, $tips[0]->getScoreTeam2());
        self::assertSame(2, $tips[0]->getScore());
        self::assertSame('FR', $tips[0]->getTeam1());
        self::assertSame('DE', $tips[0]->getTeam2());


        self::assertSame('2999-06-20:1800:RU-EN', $tips[1]->getMatchId());
        self::assertSame('2999-06-20 18:00', $tips[1]->getMatchDatetime());
        self::assertSame(4, $tips[1]->getTipTeam1());
        self::assertSame(2, $tips[1]->getTipTeam2());
        self::assertNull($tips[1]->getScoreTeam1());
        self::assertNull($tips[1]->getScoreTeam2());
        self::assertNull($tips[1]->getScore());
        self::assertSame('RU', $tips[1]->getTeam1());
        self::assertSame('EN', $tips[1]->getTeam2());


        self::assertSame('user:rockstar:tips', $redisDto[1]->getKey());

        /** @var \App\DataTransferObject\UserInfoDataProvider $info */
        $info = $redisDto[1]->getData();
        self::assertSame('rockstar', $info->getName());
        self::assertSame(2, $info->getPosition());
        self::assertSame(21, $info->getScoreSum());

        $tips = $info->getTips();
        self::assertCount(2, $tips);

        self::assertSame('2000-06-16:2100:FR-DE', $tips[0]->getMatchId());
        self::assertSame('2000-06-16 21:00', $tips[0]->getMatchDatetime());
        self::assertSame(1, $tips[0]->getTipTeam1());
        self::assertSame(2, $tips[0]->getTipTeam2());
        self::assertSame(1, $tips[0]->getScoreTeam1());
        self::assertSame(4, $tips[0]->getScoreTeam2());
        self::assertSame(1, $tips[0]->getScore());
        self::assertSame('FR', $tips[0]->getTeam1());
        self::assertSame('DE', $tips[0]->getTeam2());

        self::assertSame('2999-06-20:1800:RU-EN', $tips[1]->getMatchId());
        self::assertSame('2999-06-20 18:00', $tips[1]->getMatchDatetime());
        self::assertSame(1, $tips[1]->getTipTeam1());
        self::assertSame(5, $tips[1]->getTipTeam2());
        self::assertNull($tips[1]->getScoreTeam1());
        self::assertNull($tips[1]->getScoreTeam2());
        self::assertNull($tips[1]->getScore());
        self::assertSame('RU', $tips[1]->getTeam1());
        self::assertSame('EN', $tips[1]->getTeam2());
    }

    private function getRankingAllEventDataProvider()
    {
        $data = [
            "data" => [
                "games" => [
                    [
                        "matchId" => "2000-06-16:2100:FR-DE",
                        "team1" => "FR",
                        "team2" => "DE",
                        "matchDatetime" => "2000-06-16 21:00",
                        "scoreTeam1" => 1,
                        "scoreTeam2" => 4,
                    ],
                    [
                        "matchId" => "2999-06-20:1800:RU-EN",
                        "team1" => "RU",
                        "team2" => "EN",
                        "matchDatetime" => "2999-06-20 18:00",
                        "scoreTeam1" => null,
                        "scoreTeam2" => null,
                    ],
                    [
                        "matchId" => "2999-07-20:1800:NL-SK",
                        "team1" => "NL",
                        "team2" => "SK",
                        "matchDatetime" => "2999-07-20 18:00",
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
                                "matchId" => "2000-06-16:2100:FR-DE",
                                "score" => 2,
                                "tipTeam1" => 2,
                                "tipTeam2" => 3,
                            ],
                            [
                                "matchId" => "2999-06-20:1800:RU-EN",
                                "score" => null,
                                "tipTeam1" => 4,
                                "tipTeam2" => 2,
                            ]
                        ],
                    ],
                    [
                        "name" => "rockstar",
                        "position" => 2,
                        "scoreSum" => 21,
                        "tips" => [
                            [
                                "matchId" => "3999-06-20:1800:NO-EX",
                                "score" => null,
                                "tipTeam1" => 0,
                                "tipTeam2" => 0,
                            ],
                            [
                                "matchId" => "2000-06-16:2100:FR-DE",
                                "score" => 1,
                                "tipTeam1" => 1,
                                "tipTeam2" => 2,
                            ],
                            [
                                "matchId" => "2999-06-20:1800:RU-EN",
                                "score" => null,
                                "tipTeam1" => 1,
                                "tipTeam2" => 5,
                            ]
                        ],
                    ],
                ],
            ],
        ];

        $rankingAllEventDataProvider = new RankingAllEventDataProvider();
        $rankingAllEventDataProvider->fromArray($data);

        return $rankingAllEventDataProvider;
    }
}
