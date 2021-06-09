<?php declare(strict_types=1);

namespace App\Component\Game\Infrastructure;

use App\DataTransferObject\MatchListDataProvider;
use App\Service\Redis\RedisService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GameMessageHandler implements MessageHandlerInterface
{
    /**
     * @var \App\Service\Redis\RedisService
     */
    private RedisService $redisService;

    /**
     * @param \App\Service\Redis\RedisService $redisService
     */
    public function __construct(RedisService $redisService)
    {
        $this->redisService = $redisService;
    }

    public function __invoke(MatchListDataProvider $matchListDataProvider)
    {
        $this->redisService->set('games', json_encode($matchListDataProvider->toArray()['data']));
    }
}
