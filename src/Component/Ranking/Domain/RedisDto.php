<?php declare(strict_types=1);

namespace App\Component\Ranking\Domain;

use Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface;

final class RedisDto
{
    private string $key;

    private DataProviderInterface $data;

    /**
     * @param string $key
     * @param \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface $data
     */
    public function __construct(string $key, DataProviderInterface $data)
    {
        $this->key = $key;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
     */
    public function getData(): DataProviderInterface
    {
        return $this->data;
    }
}
