<?php declare(strict_types=1);

namespace App\Component\Tip\Application;

use App\Entity\User;
use App\Service\Message\MessageServiceInterface;

final class SendTip
{
    /**
     * @var \App\Component\Tip\Application\MappingTip
     */
    private MappingTip $mappingTip;

    /**
     * @var \App\Service\Message\MessageServiceInterface
     */
    private MessageServiceInterface $messageService;

    /**
     * @param \App\Component\Tip\Application\MappingTip $mappingTip
     * @param \App\Service\Message\MessageServiceInterface $messageService
     */
    public function __construct(
        MappingTip $mappingTip,
        MessageServiceInterface $messageService
    )
    {
        $this->mappingTip = $mappingTip;
        $this->messageService = $messageService;
    }

    public function send(string $content, User $user)
    {
        $tipEventDataProvider = $this->mappingTip->map($content, $user);
        $this->messageService->send($tipEventDataProvider);
    }
}
