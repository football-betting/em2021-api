<?php declare(strict_types=1);

namespace App\Service\RedisKey;

final class RedisKeyService
{
    /**
     * @param string $userName
     *
     * @return string
     */
    public static function getUserTips(string $userName): string
    {
        return 'user:' . $userName . ':tips';
    }

    /**
     * @return string
     */
    public static function getTable(): string
    {
        return 'table';
    }

    public static function getGames(): string
    {
        return 'games';
    }
}
