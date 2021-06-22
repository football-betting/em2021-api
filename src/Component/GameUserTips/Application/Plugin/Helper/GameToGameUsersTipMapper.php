<?php


namespace App\Component\GameUserTips\Application\Plugin\Helper;


use App\DataTransferObject\GameEventDataProvider;
use App\DataTransferObject\GameUserTipsInfoDataProvider;

class GameToGameUsersTipMapper implements GameToGameUsersTipMapperInterface
{
    public function mapGameToGameUsersTip(string $matchId, GameEventDataProvider $game): GameUserTipsInfoDataProvider
    {
        $gameUsersTipDataProvider = new GameUserTipsInfoDataProvider();

        $gameUsersTipDataProvider->setMatchId($matchId);
        $gameUsersTipDataProvider->setTeam1($game->getTeam1());
        $gameUsersTipDataProvider->setTeam2($game->getTeam2());
        $gameUsersTipDataProvider->setScoreTeam1($game->getScoreTeam1());
        $gameUsersTipDataProvider->setScoreTeam2($game->getScoreTeam2());

        return $gameUsersTipDataProvider;
    }
}