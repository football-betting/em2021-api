<?php declare(strict_types=1);

namespace App\Component\Ranking\Port;

use App\Component\Ranking\Port\Collection\UserPrivateInfo;
use App\Component\Ranking\Port\Collection\UserPublicInfo;
use App\DataTransferObject\RankingAllEventDataProvider;
use App\Service\Redis\RedisService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RankingMessageHandler implements MessageHandlerInterface
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

    public function __invoke(RankingAllEventDataProvider $rankingAllEvent)
    {
        $collection = [
            new UserPrivateInfo(),
        ];

        $redisDtoList = new RedisDtoList();
        foreach ($collection as $item) {
            $item($rankingAllEvent, $redisDtoList);
        }

        $redisDtoList = $redisDtoList->getRedisDto();

        foreach ($redisDtoList as $redisDto) {
            $this->redisService->set($redisDto->getKey(), json_encode($redisDto->getData()->toArray()));
        }
    }
}
