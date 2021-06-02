<?php declare(strict_types=1);

namespace App\Component\Tip\Application\Plugin;

use App\Component\Ranking\Application\Plugin\InformationInterface;
use App\Component\Ranking\Domain\RedisDto;
use App\Component\Ranking\Domain\RedisDtoList;
use App\DataTransferObject\RankingAllEventDataProvider;
use App\DataTransferObject\RankingInfoEventDataProvider;
use App\DataTransferObject\TipInfoDataProvider;
use App\DataTransferObject\UserInfoDataProvider;
use App\Service\RedisKey\RedisKeyService;

class UserPastTips implements InformationInterface
{
    public function get(
        RankingAllEventDataProvider $rankingAllEvent,
        RedisDtoList $redisDtoList
    ): RedisDtoList
    {
        $data = $rankingAllEvent->getData();
        $games = $this->getGames($data);

        foreach ($data->getUsers() as $user) {
            $userInfo = new UserInfoDataProvider();
            $userInfo->setPosition($user->getPosition());
            $userInfo->setName($user->getName());
            $userInfo->setScoreSum($user->getScoreSum());

            foreach ($user->getTips() as $userTip) {
                if (!isset($games[$userTip->getMatchId()])) {
                    continue;
                }

                $matchDateTime = $games[$userTip->getMatchId()]->getMatchDatetime();
                if (new \DateTime($matchDateTime) >= new \DateTime()) {
                    continue;
                }

                $tip = new TipInfoDataProvider();

                $tip->setMatchId($userTip->getMatchId());
                $tip->setTipTeam1($userTip->getTipTeam1());
                $tip->setTipTeam2($userTip->getTipTeam2());

                $game = $games[$userTip->getMatchId()];
                $tip->setScoreTeam1($game->getScoreTeam1());
                $tip->setScoreTeam2($game->getScoreTeam2());
                $tip->setTeam1($game->getTeam1());
                $tip->setTeam2($game->getTeam2());
                $tip->setMatchDatetime($game->getMatchDatetime());

                $userInfo->addTip($tip);
            }

            $redisDtoList->addRedisDto(
                new RedisDto(
                    RedisKeyService::getUserPastTips($user->getName()),
                    $userInfo
                )
            );
        }

        return $redisDtoList;
    }

    /**
     * @param \App\DataTransferObject\RankingInfoEventDataProvider $rankingAllEvent
     *
     * @return \App\DataTransferObject\GameEventDataProvider[]
     */
    private function getGames(RankingInfoEventDataProvider $data): array
    {
        $games = [];

        foreach ($data->getGames() as $game) {
            $games[$game->getMatchId()] = $game;
        }

        return $games;
    }
}
