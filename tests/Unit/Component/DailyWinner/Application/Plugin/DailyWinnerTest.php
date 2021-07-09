<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\DailyWinner\Application\Plugin;

use App\Component\DailyWinner\Application\Plugin\DailyWinner;
use App\Component\Ranking\Domain\RedisDtoList;
use App\DataTransferObject\DailyWinnerListDataProvider;
use App\DataTransferObject\RankingAllEventDataProvider;
use PHPUnit\Framework\TestCase;

class DailyWinnerTest extends TestCase
{
    public function test()
    {
        $rating = $this->getRankingAllEventDataProvider();

        $dailyWinnerPlugin = new DailyWinner();
        $redisDtoList = $dailyWinnerPlugin->get(
            $rating,
            new RedisDtoList()
        );

        $redisDtoList = $redisDtoList->getRedisDto();

        self::assertCount(1, $redisDtoList);

        /** @var DailyWinnerListDataProvider $data */
        $data = $redisDtoList[0]->getData();

        self::assertInstanceOf(DailyWinnerListDataProvider::class, $data);

        $checkData = $data->getData();

        self::assertCount(3, $checkData);

        self::assertCount(2, $checkData[0]->getUsers());
        self::assertSame(6, $checkData[0]->getPoints());
        self::assertSame(['ninja', 'rockstar'], $checkData[0]->getUsers());
        self::assertSame('2021-06-20', $checkData[0]->getMatchDate());

        self::assertCount(1, $checkData[1]->getUsers());
        self::assertSame(4, $checkData[1]->getPoints());
        self::assertSame(['rockstar'], $checkData[1]->getUsers());
        self::assertSame('2021-06-21', $checkData[1]->getMatchDate());

        self::assertCount(1, $checkData[2]->getUsers());
        self::assertSame(5, $checkData[2]->getPoints());
        self::assertSame(['motherSoccer'], $checkData[2]->getUsers());
        self::assertSame('2021-06-22', $checkData[2]->getMatchDate());
    }

    private function getRankingAllEventDataProvider(): RankingAllEventDataProvider
    {
        $data = [
            "data" => [
                "users" => [
                    [
                        "name" => "ninja",
                        "tips" => [
                            [
                                "matchId" => "2021-06-20:1800:NL-SK",
                                "score" => 2,
                            ],
                            [
                                "matchId" => "2021-06-20:2100:DE-PL",
                                "score" => 2,
                            ],
                            [
                                "matchId" => "2021-06-20:2100:IT-DE",
                                "score" => 2,
                            ],
                            [
                                "matchId" => "2021-06-21:1800:NL-SK",
                                "score" => 1,
                            ],
                            [
                                "matchId" => "2021-06-22:2100:DE-PL",
                                "score" => 1,
                            ],
                            [
                                "matchId" => "2021-06-22:2100:IT-DE",
                                "score" => 1,
                            ],
                        ],
                    ],
                    [
                        "name" => "rockstar",
                        "tips" => [
                            [
                                "matchId" => "2021-06-20:1800:NL-SK",
                                "score" => 2,
                            ],
                            [
                                "matchId" => "2021-06-20:2100:DE-PL",
                                "score" => 2,
                            ],
                            [
                                "matchId" => "2021-06-20:2100:IT-DE",
                                "score" => 2,
                            ],
                            [
                                "matchId" => "2021-06-21:1800:NL-SK",
                                "score" => 4,
                            ],
                            [
                                "matchId" => "2021-06-22:2100:DE-PL",
                                "score" => 1,
                            ],
                            [
                                "matchId" => "2021-06-22:2100:IT-DE",
                                "score" => 1,
                            ],
                            [
                                "matchId" => "2021-06-23:2100:IT-DE",
                                "score" => 0,
                            ],
                        ],
                    ],
                    [
                        "name" => "motherSoccer",
                        "tips" => [
                            [
                                "matchId" => "2021-06-20:1800:NL-SK",
                                "score" => 1,
                            ],
                            [
                                "matchId" => "2021-06-20:2100:DE-PL",
                                "score" => 2,
                            ],
                            [
                                "matchId" => "2021-06-20:2100:IT-DE",
                                "score" => 2,
                            ],
                            [
                                "matchId" => "2021-06-21:1800:NL-SK",
                                "score" => 1,
                            ],
                            [
                                "matchId" => "2021-06-22:2100:DE-PL",
                                "score" => 3,
                            ],
                            [
                                "matchId" => "2021-06-22:2100:IT-DE",
                                "score" => 2,
                            ],
                            [
                                "matchId" => "2021-06-23:2100:IT-DE",
                                "score" => 0,
                            ],
                        ],
                    ],
                    [
                        "name" => "master",
                        "tips" => [
                            [
                                "matchId" => "2021-06-20:1800:NL-SK",
                                "score" => 1,
                            ],
                            [
                                "matchId" => "2021-06-20:2100:DE-PL",
                                "score" => 1,
                            ],
                            [
                                "matchId" => "2021-06-20:2100:IT-DE",
                                "score" => 1,
                            ],
                            [
                                "matchId" => "2021-06-21:1800:NL-SK",
                                "score" => 1,
                            ],
                            [
                                "matchId" => "2021-06-22:2100:DE-PL",
                                "score" => 1,
                            ],
                            [
                                "matchId" => "2021-06-22:2100:IT-DE",
                                "score" => 1,
                            ],
                            [
                                "matchId" => "2021-06-23:2100:IT-DE",
                                "score" => 0,
                            ],
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
