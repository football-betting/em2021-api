<?php

namespace App\Component\GameUserTips\Application\Plugin\Helper;

use App\DataTransferObject\TipInfoEventDataProvider;

interface TipValidatorInterface
{
    /**
     * @param array $tips
     * @param string $matchId
     * @return \App\DataTransferObject\TipInfoEventDataProvider|null
     */
    public function getTips(array $tips, string $matchId): ?TipInfoEventDataProvider;
}