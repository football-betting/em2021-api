<?php declare(strict_types=1);

namespace App\Service\Redis;

use Predis\Client;

final class RedisService implements RedisServiceInterface
{
    private string $prefix;

    /**
     * @var \Predis\Client
     */
    private Client $client;

    /**
     * @param \App\Service\Redis\RedisFactory $redisFactory
     */
    public function __construct(RedisFactory $redisFactory)
    {
        $this->client = $redisFactory->getClient();
        $this->prefix = $redisFactory->getPrefix();
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->client->set($key, $value);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function get(string $key): string
    {
        return (string)$this->client->get($key);
    }

    public function getAll(): array
    {
        $keys = $this->getKeys('*');
        return $this->mget($keys);
    }

    public function getKeys(string $pattern): array
    {
        return (array)$this->client->keys($pattern);
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function mget(array $keys): array
    {
        return (array)$this->client->mget($keys);
    }

    /**
     * @param string $key
     */
    public function delete(string $key): void
    {
        $this->client->del([$key]);
    }

    /**
     * @param string $key
     */
    public function deleteAll(): void
    {
        $keys = $this->getKeys('*');
        foreach ($keys as $id => $key) {
            $keys[$id] = str_replace($this->prefix, '', $key);
        }

        if(count($keys) > 0) {
            $this->client->del($keys);
        }
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

}


