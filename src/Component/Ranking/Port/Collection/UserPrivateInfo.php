<?php declare(strict_types=1);

namespace App\Component\Ranking\Port\Collection;

use App\Component\Ranking\Port\RedisDto;
use App\Component\Ranking\Port\RedisDtoList;
use App\DataTransferObject\RankingAllEventDataProvider;
use App\DataTransferObject\TipInfoDataProvider;
use App\DataTransferObject\UserInfoDataProvider;

class UserPrivateInfo
{
    public function __invoke(RankingAllEventDataProvider $rankingAllEvent, RedisDtoList $redisDtoList): void
    {
        $games = [];
        $data = $rankingAllEvent->getData();
        foreach ($data->getGames() as $game) {
            $games[$game->getMatchId()] = $game;
        }

        foreach ($data->getUsers() as $user) {
            $userInfo = new UserInfoDataProvider();
            $userInfo->setPosition($user->getPosition());
            $userInfo->setName($user->getName());

            foreach ($user->getTips() as $userTip) {
                if(!isset($games[$userTip->getMatchId()])) {
                    continue;
                }

                $tip = new TipInfoDataProvider();

                $tip->setMatchId($userTip->getMatchId());
                $tip->setTipTeam1($userTip->getTipTeam1());
                $tip->setTipTeam2($userTip->getTipTeam2());

                $game = $games[$userTip->getMatchId()];
                $tip->setScoreTeam1($game->getScoreTeam1());
                $tip->setScoreTeam2($game->getScoreTeam2());

                $userInfo->addTip($tip);
            }

            $redisDtoList->addRedisDto(
                new RedisDto(
                    'user:' . $user->getName() . ':private',
                    $userInfo
                )
            );
        }
    }
}
