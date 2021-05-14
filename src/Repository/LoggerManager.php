<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Logger;
use Doctrine\ORM\EntityManagerInterface;
use Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface;

final class LoggerManager implements LoggerManagerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private EntityManagerInterface $objectManager;

    public function __construct(EntityManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface $dataProvider
     */
    public function save(DataProviderInterface $dataProvider): void
    {
        $logger = new Logger();
        $logger->setData($dataProvider->toArray());
        $logger->setCreatedAt();

        $this->objectManager->persist($logger);
        $this->objectManager->flush();
    }
}
