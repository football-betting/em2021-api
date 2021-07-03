<?php

namespace App\Component\GameUserTips\Application\Plugin\Helper;

interface DateValidatorInterface
{
    public function isDateValid(string $matchId): bool;
}