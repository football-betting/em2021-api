<?php declare(strict_types=1);

namespace App\Component\Ranking\Infrastructure;

use App\Component\Ranking\Application\InformationPreparer;
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
     * @var \App\Component\Ranking\Application\InformationPreparer
     */
    private InformationPreparer $informationPreparer;

    /**
     * @param \App\Service\Redis\RedisService $redisService
     */
    public function __construct(RedisService $redisService, InformationPreparer $informationPreparer)
    {
        $this->redisService = $redisService;
        $this->informationPreparer = $informationPreparer;
    }

    public function __invoke(RankingAllEventDataProvider $rankingAllEvent)
    {
        $redisDtoList = $this->informationPreparer->get($rankingAllEvent);

        foreach ($redisDtoList->getRedisDto() as $redisDto) {
            $this->redisService->set($redisDto->getKey(), json_encode($redisDto->getData()->toArray()));
        }
    }
}
