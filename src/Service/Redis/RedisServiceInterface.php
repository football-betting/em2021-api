<?php declare(strict_types=1);

namespace App\Service\Redis;

interface RedisServiceInterface
{
    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set(string $key, $value): void;

    /**
     * @param string $key
     *
     * @return string
     */
    public function get(string $key): string;

    public function getAll(): array;

    public function getKeys(string $pattern): array;

    /**
     * @param array $keys
     *
     * @return array
     */
    public function mget(array $keys): array;

    /**
     * @param string $key
     */
    public function delete(string $key): void;
}
