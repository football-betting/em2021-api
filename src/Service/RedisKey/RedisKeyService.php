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

    /**
     * @return string
     */
    public static function getGames(): string
    {
        return 'games';
    }

    /**
     * @param string $matchId
     * @return string
     */
    public static function getGameUsersTip(string $matchId):string
    {
        return 'game:' . $matchId;
    }
}
