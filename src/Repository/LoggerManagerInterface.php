<?php declare(strict_types=1);

namespace App\Repository;

use Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface;

interface LoggerManagerInterface
{
    /**
     * @param \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface $dataProvider
     */
    public function save(DataProviderInterface $dataProvider): void;
}
