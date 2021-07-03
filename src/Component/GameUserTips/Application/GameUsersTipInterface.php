<?php

namespace App\Component\GameUserTips\Application;

use App\DataTransferObject\GameUserTipsInfoDataProvider;
use App\DataTransferObject\UserInfoEventDataProvider;

interface GameUsersTipInterface
{
    /**
     * @param string $matchId
     *
     * @return \App\DataTransferObject\GameUserTipsInfoDataProvider
     * @throws \Exception
     */
    public function getPastGameUsersTip(string $matchId):GameUserTipsInfoDataProvider;
}