<?php


namespace App\Component\GameUserTips\Application\Plugin\Helper;


use App\DataTransferObject\TipInfoEventDataProvider;

class TipValidator implements TipValidatorInterface
{
    /**
     * @param array $tips
     * @param string $matchId
     * @return \App\DataTransferObject\TipInfoEventDataProvider|null
     */
    public function getTips(array $tips, string $matchId): ?TipInfoEventDataProvider
    {
        foreach ($tips as $tip) {
            if ($tip->getMatchId() === $matchId) {
                return $tip;
                break;
            }
        }
        return null;
    }
}