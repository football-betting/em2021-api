<?php declare(strict_types=1);

namespace App\Component\UserRating\Application\Plugin;

use App\Component\Ranking\Application\Plugin\InformationInterface;
use App\Component\Ranking\Domain\RedisDto;
use App\Component\Ranking\Domain\RedisDtoList;
use App\DataTransferObject\RankingAllEventDataProvider;
use App\DataTransferObject\RankingInfoEventDataProvider;
use App\DataTransferObject\UserInfoEventDataProvider;
use App\Service\RedisKey\RedisKeyService;

class UserRating implements InformationInterface
{
    public function get(RankingAllEventDataProvider $rankingAllEvent, RedisDtoList $redisDtoList): RedisDtoList
    {
        $data = $rankingAllEvent->getData();

        $rankingInfoEventDataProvider = new RankingInfoEventDataProvider();
        foreach ($data->getUsers() as $user) {
            $userRating = new UserInfoEventDataProvider();

            $userRating->setPosition($user->getPosition());
            $userRating->setName($user->getName());

            $userRating->setScoreSum($user->getScoreSum());
            $userRating->setSumWinExact($user->getSumWinExact());
            $userRating->setSumTeam($user->getSumTeam());
            $userRating->setSumScoreDiff($user->getSumScoreDiff());

            $rankingInfoEventDataProvider->addUser($userRating);
        }

        file_put_contents(__DIR__ . '/../table.json', json_encode($rankingInfoEventDataProvider->toArray()));

        $redisDtoList->addRedisDto(
            new RedisDto(
                RedisKeyService::getTable(),
                $rankingInfoEventDataProvider
            )
        );

        return $redisDtoList;
    }

}
