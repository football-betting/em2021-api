<?php declare(strict_types=1);

namespace App\Component\Ranking\Port;

use App\DataTransferObject\RankingAllEventDataProvider;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RankingMessageHandler implements MessageHandlerInterface
{
    public function __invoke(RankingAllEventDataProvider $rankingAllEvent)
    {
        $test = 'hallo';
    }
}
