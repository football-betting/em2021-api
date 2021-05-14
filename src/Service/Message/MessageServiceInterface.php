<?php declare(strict_types=1);

namespace App\Service\Message;

use Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface;

interface MessageServiceInterface
{
    /**
     * @param \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface $dataProvider
     */
    public function send(DataProviderInterface $dataProvider);
}
