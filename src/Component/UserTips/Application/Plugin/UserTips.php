<?php declare(strict_types=1);

namespace App\Component\UserTips\Application\Plugin;

use App\Component\Ranking\Application\Plugin\InformationInterface;
use App\Component\Ranking\Domain\RedisDto;
use App\Component\Ranking\Domain\RedisDtoList;
use App\DataTransferObject\RankingAllEventDataProvider;
use App\DataTransferObject\RankingInfoEventDataProvider;
use App\DataTransferObject\TipInfoDataProvider;
use App\DataTransferObject\UserInfoDataProvider;
use App\Service\RedisKey\RedisKeyService;

class UserTips implements InformationInterface
{
    public function get(
        RankingAllEventDataProvider $rankingAllEvent,
        RedisDtoList $redisDtoList
    ): RedisDtoList
    {
        $data = $rankingAllEvent->getData();

        foreach ($data->getUsers() as $user) {
            $redisDtoList->addRedisDto(
                new RedisDto(
                    RedisKeyService::getUserTips($user->getName()),
                    $user
                )
            );
        }

        return $redisDtoList;
    }
}
