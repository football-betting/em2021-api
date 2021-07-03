<?php declare(strict_types=1);

namespace App\Component\Game\Application\Plugin;

use App\Component\Ranking\Application\Plugin\InformationInterface;
use App\Component\Ranking\Domain\RedisDto;
use App\Component\Ranking\Domain\RedisDtoList;
use App\DataTransferObject\GameEventListDataProvider;
use App\DataTransferObject\RankingAllEventDataProvider;
use App\Service\RedisKey\RedisKeyService;

class GamePlugins implements InformationInterface
{
    public function get(RankingAllEventDataProvider $rankingAllEvent, RedisDtoList $redisDtoList): RedisDtoList
    {
        $games = $rankingAllEvent->getData()->getGames();

        $gameEventListDataProvider = new GameEventListDataProvider();
        $gameEventListDataProvider->setGames($games);

        $redisDtoList->addRedisDto(
            new RedisDto(
                RedisKeyService::getGames(),
                $gameEventListDataProvider
            )
        );

        return $redisDtoList;
    }

}
