<?php declare(strict_types=1);

namespace App\Component\UserRating\Application\Plugin;

use App\Component\Ranking\Application\Plugin\InformationInterface;
use App\Component\Ranking\Domain\RedisDto;
use App\Component\Ranking\Domain\RedisDtoList;
use App\DataTransferObject\RankingAllEventDataProvider;
use App\DataTransferObject\UserRatingDataProvider;
use App\DataTransferObject\UserRatingListDataProvider;
use App\Service\RedisKey\RedisKeyService;

class UserRating implements InformationInterface
{
    public function get(RankingAllEventDataProvider $rankingAllEvent, RedisDtoList $redisDtoList): RedisDtoList
    {
        $data = $rankingAllEvent->getData();

        $userRatingListDataProvider = new UserRatingListDataProvider();
        foreach ($data->getUsers() as $user) {
            $userRating = new UserRatingDataProvider();
            $userRating->setPosition($user->getPosition());
            $userRating->setName($user->getName());
            $userRating->setScoreSum($user->getScoreSum());

            $userRatingListDataProvider->addUser($userRating);
        }

        $redisDtoList->addRedisDto(
            new RedisDto(
                RedisKeyService::getTable(),
                $userRatingListDataProvider
            )
        );

        return $redisDtoList;
    }

}
