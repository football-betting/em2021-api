<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\UserRating\Application\Plugin;

use App\Component\Ranking\Domain\RedisDtoList;
use App\Component\UserRating\Application\Plugin\UserRating;
use App\DataTransferObject\RankingAllEventDataProvider;
use App\DataTransferObject\UserRatingListDataProvider;
use App\Service\RedisKey\RedisKeyService;
use PHPUnit\Framework\TestCase;

class UserRatingTest extends TestCase
{
    public function testUserRating()
    {
        $userRating = new UserRating();

//        $rankingAllEventDataProvider = $this->getRankingAllEventDataProvider();
        $rankingAllEventDataProvider = new RankingAllEventDataProvider();
        $rankingAllEventDataProvider->fromArray(\Safe\json_decode(file_get_contents(__DIR__ . '/ranking_1623772282.0495.json'), true));
        $redisDtoList = $userRating->get($rankingAllEventDataProvider, new RedisDtoList());

        $redisDto = $redisDtoList->getRedisDto()[0];

        self::assertSame(RedisKeyService::getTable(), $redisDto->getKey());

        /** @var UserRatingListDataProvider $userRatingListDataProvider */
        $userRatingListDataProvider = $redisDto->getData();

        self::assertInstanceOf(UserRatingListDataProvider::class, $userRatingListDataProvider);
        self::assertCount(3, $userRatingListDataProvider->getUsers());

        $users = $userRatingListDataProvider->getUsers();

        self::assertSame($rankingAllEventDataProvider->getData()->getUsers()[0]->getName(), $users[0]->getName());
        self::assertSame($rankingAllEventDataProvider->getData()->getUsers()[0]->getPosition(), $users[0]->getPosition());
        self::assertSame($rankingAllEventDataProvider->getData()->getUsers()[0]->getScoreSum(), $users[0]->getScoreSum());

        self::assertSame($rankingAllEventDataProvider->getData()->getUsers()[1]->getName(), $users[1]->getName());
        self::assertSame($rankingAllEventDataProvider->getData()->getUsers()[1]->getPosition(), $users[1]->getPosition());
        self::assertSame($rankingAllEventDataProvider->getData()->getUsers()[1]->getScoreSum(), $users[1]->getScoreSum());

        self::assertSame($rankingAllEventDataProvider->getData()->getUsers()[2]->getName(), $users[2]->getName());
        self::assertSame($rankingAllEventDataProvider->getData()->getUsers()[2]->getPosition(), $users[2]->getPosition());
        self::assertSame($rankingAllEventDataProvider->getData()->getUsers()[2]->getScoreSum(), $users[2]->getScoreSum());
    }

    private function getRankingAllEventDataProvider()
    {
        $data = [
            "data" => [
                "users" => [
                    [
                        "name" => "ninja",
                        "position" => 1,
                        "scoreSum" => 24,
                        "tips" => [],
                    ],
                    [
                        "name" => "rockstar",
                        "position" => 2,
                        "scoreSum" => 21,
                        "tips" => [],
                    ],
                    [
                        "name" => "motherSoccer",
                        "position" => 3,
                        "scoreSum" => 15,
                        "tips" => [],
                    ],
                ],
            ],
        ];

        $rankingAllEventDataProvider = new RankingAllEventDataProvider();
        $rankingAllEventDataProvider->fromArray($data);

        return $rankingAllEventDataProvider;
    }
}
