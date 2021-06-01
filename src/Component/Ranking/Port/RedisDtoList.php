<?php declare(strict_types=1);

namespace App\Component\Ranking\Port;

final class RedisDtoList
{
    private $redisDto = [];

    /**
     * @return RedisDto[]
     */
    public function getRedisDto(): array
    {
        return $this->redisDto;
    }

    public function addRedisDto(RedisDto $redisDto): void
    {
        $this->redisDto[] = $redisDto;
    }
}
