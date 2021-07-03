<?php

namespace App\Component\GameUserTips\Application\Plugin\Helper;

use App\DataTransferObject\GameEventDataProvider;
use App\DataTransferObject\GameUserTipsInfoDataProvider;

interface GameToGameUsersTipMapperInterface
{
    public function mapGameToGameUsersTip(string $matchId, GameEventDataProvider $game): GameUserTipsInfoDataProvider;
}