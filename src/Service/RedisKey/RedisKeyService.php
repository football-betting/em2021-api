<?php declare(strict_types=1);

namespace App\Service\RedisKey;

final class RedisKeyService
{
    /**
     * @param string $userName
     *
     * @return string
     */
    public static function getUserPastTips(string $userName): string
    {
        return 'user:' . $userName . ':past_tips';
    }

    /**
     * @param string $userName
     *
     * @return string
     */
    public static function getUserTips(string $userName): string
    {
        return 'user:' . $userName . ':tips';
    }
}
