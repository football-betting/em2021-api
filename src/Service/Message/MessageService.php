<?php declare(strict_types=1);

namespace App\Service\Message;

use App\Repository\LoggerManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface;

final class MessageService implements MessageServiceInterface
{
    /**
     * @var \Symfony\Component\Messenger\MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * @var \App\Repository\LoggerManagerInterface
     */
    private LoggerManagerInterface $loggerManager;

    /**
     * @param \Symfony\Component\Messenger\MessageBusInterface $messageBus
     * @param \App\Repository\LoggerManagerInterface $loggerManager
     */
    public function __construct(
        MessageBusInterface $messageBus,
        LoggerManagerInterface $loggerManager
    )
    {
        $this->messageBus = $messageBus;
        $this->loggerManager = $loggerManager;
    }

    /**
     * @param \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface $dataProvider
     */
    public function send(DataProviderInterface $dataProvider)
    {
        $this->loggerManager->save($dataProvider);
        $this->messageBus->dispatch($dataProvider);
    }
}
