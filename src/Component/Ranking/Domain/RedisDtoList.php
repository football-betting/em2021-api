<?php declare(strict_types=1);

namespace App\Component\Ranking\Domain;

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
