<?php declare(strict_types=1);

namespace App\Component\GameUserTips\Application\Plugin;

use App\Component\Ranking\Application\Plugin\InformationInterface;
use App\Component\Ranking\Domain\RedisDto;
use App\Component\Ranking\Domain\RedisDtoList;
use App\DataTransferObject\GameEventDataProvider;
use App\DataTransferObject\GameUserTipsInfoDataProvider;
use App\DataTransferObject\RankingAllEventDataProvider;
use App\DataTransferObject\TipInfoEventDataProvider;
use App\DataTransferObject\UserTipDataProvider;
use App\Service\RedisKey\RedisKeyService;

class GameUserTips implements InformationInterface
{
    /**
     * @param \App\DataTransferObject\RankingAllEventDataProvider $rankingAllEvent
     * @param \App\Component\Ranking\Domain\RedisDtoList $redisDtoList
     *
     * @return \App\Component\Ranking\Domain\RedisDtoList
     */
    public function get(RankingAllEventDataProvider $rankingAllEvent, RedisDtoList $redisDtoList): RedisDtoList
    {
        $data = $rankingAllEvent->getData();
        $users = $data->getUsers();
        $games = $data->getGames();

        $matchToUserMatch = [];

        foreach ($users as $user) {
            foreach ($user->getTips() as $tip) {
                $matchToUserMatch[$tip->getMatchId()][$user->getName()] = $tip;
            }
        }

        foreach ($games as $game) {
            $matchId = $game->getMatchId();

            if ($this->isMatchDateInPast($matchId)) {

                $gameUsersTipDataProvider = $this->mapGameToGameUsersTip($matchId, $game);

                if (isset($matchToUserMatch[$matchId])) {
                    /**
                     * @var string $user
                     * @var TipInfoEventDataProvider $tip
                     */
                    foreach ($matchToUserMatch[$matchId] as $userName => $tip) {
                        $userTip = new UserTipDataProvider();
                        $userTip->setName($userName);
                        $userTip->setTipTeam1($tip->getTipTeam1());
                        $userTip->setTipTeam2($tip->getTipTeam2());
                        $userTip->setScore($tip->getScore());

                        $gameUsersTipDataProvider->addUserTip($userTip);
                    }
                }

                $redisDtoList->addRedisDto(new RedisDto(RedisKeyService::getGameUsersTip($matchId), $gameUsersTipDataProvider));
            }
        }

        return $redisDtoList;
    }

    /**
     * @param string $matchId
     * @param \App\DataTransferObject\GameEventDataProvider $game
     *
     * @return \App\DataTransferObject\GameUserTipsInfoDataProvider
     */
    private function mapGameToGameUsersTip(string $matchId, GameEventDataProvider $game): GameUserTipsInfoDataProvider
    {
        $gameUsersTipDataProvider = new GameUserTipsInfoDataProvider();

        $gameUsersTipDataProvider->setMatchId($matchId);
        $gameUsersTipDataProvider->setTeam1($game->getTeam1());
        $gameUsersTipDataProvider->setTeam2($game->getTeam2());
        $gameUsersTipDataProvider->setScoreTeam1($game->getScoreTeam1());
        $gameUsersTipDataProvider->setScoreTeam2($game->getScoreTeam2());

        return $gameUsersTipDataProvider;
    }

    /**
     * @param string $matchId
     *
     * @return bool
     */
    private function isMatchDateInPast(string $matchId): bool
    {
        $matchId = substr($matchId, 0, -6);
        $matchId = str_replace(':', ' ', $matchId);

        $matchDatetime = (int)(new \DateTime($matchId))->format('YmdHi');
        $now = (int)(new \DateTime('now'))->format('YmdHi');

        return $matchDatetime <= $now;
    }
}
