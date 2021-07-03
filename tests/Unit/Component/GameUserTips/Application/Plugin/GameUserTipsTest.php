<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\GameUserTips\Application\Plugin;

use App\Component\GameUserTips\Application\Plugin\GameUserTips;
use App\Component\Ranking\Domain\RedisDtoList;
use App\DataTransferObject\GameUserTipsInfoDataProvider;
use App\DataTransferObject\RankingAllEventDataProvider;
use PHPUnit\Framework\TestCase;

class GameUserTipsTest extends TestCase
{
    public function testGameUserTipsTest()
    {
        $userPastTips = new GameUserTips();

        $rankingAllEventDataProvider = $this->getRankingAllEventDataProvider();

        $redisDtoList = $userPastTips->get($rankingAllEventDataProvider, new RedisDtoList());

        $redisDto = $redisDtoList->getRedisDto();

        self::assertCount(2, $redisDto);


        self::assertSame('game:2000-06-16:2100:FR-DE', $redisDto[0]->getKey());
        self::assertSame('game:2000-06-18:2100:PL-EN', $redisDto[1]->getKey());

        /** @var \App\DataTransferObject\GameUserTipsInfoDataProvider $game */
        $game = $redisDto[0]->getData();

        self::assertInstanceOf(GameUserTipsInfoDataProvider::class, $game);

        self::assertSame('2000-06-16:2100:FR-DE', $game->getMatchId());
        self::assertSame('FR', $game->getTeam1());
        self::assertSame('DE', $game->getTeam2());
        self::assertSame(1, $game->getScoreTeam1());
        self::assertSame(4, $game->getScoreTeam2());

        $tips = $game->getUsersTip();

        self::assertCount(2, $tips);

        $userTip = $tips[0];
        self::assertSame(2, $userTip->getScore());
        self::assertSame(2, $userTip->getTipTeam1());
        self::assertSame(3, $userTip->getTipTeam2());
        self::assertSame('ninja', $userTip->getName());

        $userTip = $tips[1];
        self::assertSame(4, $userTip->getScore());
        self::assertSame(0, $userTip->getTipTeam1());
        self::assertSame(1, $userTip->getTipTeam2());
        self::assertSame('rockstar', $userTip->getName());

        /** @var \App\DataTransferObject\GameUserTipsInfoDataProvider $game */
        $game = $redisDto[1]->getData();

        self::assertInstanceOf(GameUserTipsInfoDataProvider::class, $game);

        self::assertSame('2000-06-18:2100:PL-EN', $game->getMatchId());
        self::assertSame('PL', $game->getTeam1());
        self::assertSame('EN', $game->getTeam2());
        self::assertSame(0, $game->getScoreTeam1());
        self::assertSame(3, $game->getScoreTeam2());

        $tips = $game->getUsersTip();

        self::assertCount(3, $tips);

        $userTip = $tips[0];
        self::assertSame(1, $userTip->getScore());
        self::assertSame(2, $userTip->getTipTeam1());
        self::assertSame(4, $userTip->getTipTeam2());
        self::assertSame('ninja', $userTip->getName());

        $userTip = $tips[1];
        self::assertSame(0, $userTip->getScore());
        self::assertSame(null, $userTip->getTipTeam1());
        self::assertSame(null, $userTip->getTipTeam2());
        self::assertSame('rockstar', $userTip->getName());

        $userTip = $tips[2];
        self::assertSame(2, $userTip->getScore());
        self::assertSame(2, $userTip->getTipTeam1());
        self::assertSame(2, $userTip->getTipTeam2());
        self::assertSame('john_doe', $userTip->getName());
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
                        "matchId" => "2000-06-18:2100:PL-EN",
                        "team1" => "PL",
                        "team2" => "EN",
                        "matchDatetime" => "2000-06-18 21:00",
                        "scoreTeam1" => 0,
                        "scoreTeam2" => 3,
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
                                "matchId" => "2000-06-18:2100:PL-EN",
                                "score" => 1,
                                "tipTeam1" => 2,
                                "tipTeam2" => 4,
                            ]
                        ],
                    ],
                    [
                        "name" => "rockstar",
                        "position" => 2,
                        "scoreSum" => 21,
                        "tips" => [
                            [
                                "matchId" => "2000-06-16:2100:FR-DE",
                                "score" => 4,
                                "tipTeam1" => 0,
                                "tipTeam2" => 1,
                            ],
                            [
                                "matchId" => "2000-06-18:2100:PL-EN",
                                "score" => 0,
                                "tipTeam1" => null,
                                "tipTeam2" => null,
                            ],
                            [
                                "matchId" => "2999-06-20:1800:RU-EN",
                                "score" => null,
                                "tipTeam1" => 1,
                                "tipTeam2" => 5,
                            ]
                        ],
                    ],
                    [
                        "name" => "john_doe",
                        "position" => 3,
                        "scoreSum" => 1,
                        "tips" => [
                            [
                                "matchId" => "2000-06-18:2100:PL-EN",
                                "score" => 2,
                                "tipTeam1" => 2,
                                "tipTeam2" => 2,
                            ],
                            [
                                "matchId" => "2999-06-20:1800:RU-EN",
                                "score" => null,
                                "tipTeam1" => 1,
                                "tipTeam2" => 5,
                            ],
                            [
                                "matchId" => "2999-07-20:1800:NL-SK",
                                "score" => null,
                                "tipTeam1" => 2,
                                "tipTeam2" => 1,
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
