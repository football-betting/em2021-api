<?php


namespace App\Component\GameUserTips\Application\Plugin\Helper;


class DateValidator implements DateValidatorInterface
{
    public function isDateValid(string $matchId): bool
    {
        $matchDatetime = $this->formatDate($matchId);
        $now = (int)(new \DateTime('now'))->format('YmdHi');
        return !($matchDatetime <= $now);
    }

    /**
     * @param string $date
     * @return int
     * @throws \Exception
     */
    private function formatDate(string $matchId): int
    {
        $matchId = substr($matchId, 0, -6);
        $matchId = str_replace(':', ' ', $matchId);
        return (int)(new \DateTime($matchId))->format('YmdHi');
    }

}