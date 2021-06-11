<?php declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Service\Redis\RedisServiceInterface;

class RedisDummy implements RedisServiceInterface
{
    /** @var string[]  */
    private $data = [];

    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function get(string $key): string
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return '';
    }

    public function getAll(): array
    {
        throw new \RuntimeException('not implemented yet');
    }

    public function getKeys(string $pattern): array
    {
        throw new \RuntimeException('not implemented yet');
    }

    public function mget(array $keys): array
    {
        throw new \RuntimeException('not implemented yet');
    }

    public function delete(string $key): void
    {
        throw new \RuntimeException('not implemented yet');
    }

    public function getPrefix(): string
    {
        // TODO: Implement getPrefix() method.
        return '';
    }
}
