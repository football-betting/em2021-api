<?php


namespace App\Component\GameUserTips\Application;


use App\DataTransferObject\GameUserTipsInfoDataProvider;
use App\DataTransferObject\UserInfoEventDataProvider;
use App\Service\Redis\RedisServiceInterface;
use App\Service\RedisKey\RedisKeyService;

class GameUsersTip implements GameUsersTipInterface
{
    /**
     * @var \App\Service\Redis\RedisServiceInterface $redisService
     */
    private RedisServiceInterface $redisService;


    /**
     * @param \App\Service\Redis\RedisServiceInterface $redisService
     */
    public function __construct(RedisServiceInterface $redisService)
    {
        $this->redisService = $redisService;
    }

    /**
     * @param string $matchId
     * @return \App\DataTransferObject\GameUserTipsInfoDataProvider
     */
    public function getPastGameUsersTip(string $matchId): GameUserTipsInfoDataProvider
    {
        $matchUsersTipInfo = $this->getRedisMatchUsersTipInfo($matchId);
        /** $tips = $matchUsersTipInfo->getUsersTip();

        foreach ($tips as $key => $tip) {
            $matchDatetime = $this->formatDate($tip->getMatchId());
            $now = (int)(new \DateTime('now'))->format('YmdHi');
            if ($matchDatetime > $now) {
                unset($tips[$key]);
            }
        }

        $matchUsersTipInfo->setUsersTip($tips); **/

        return $matchUsersTipInfo;
    }

    /**
     * @param $matchId
     *
     * @return \App\DataTransferObject\GameUserTipsInfoDataProvider
     */
    private function getRedisMatchUsersTipInfo($matchId): GameUserTipsInfoDataProvider
    {
        $gameUsersTipRedisKey = RedisKeyService::getGameUsersTip($matchId);
        $redisInfo = $this->redisService->get($gameUsersTipRedisKey);

        $arrayInfo = json_decode($redisInfo, true);
        if (JSON_ERROR_NONE !== json_last_error() || count($arrayInfo) === 0) {
            $gameUserTipsInfoDataProvider = new  GameUserTipsInfoDataProvider();
            $gameUserTipsInfoDataProvider->fromArray($arrayInfo);
            return $gameUserTipsInfoDataProvider;
        }

        $gameUserTipsInfoDataProvider = new GameUserTipsInfoDataProvider();
        $gameUserTipsInfoDataProvider->fromArray($arrayInfo);

        return $gameUserTipsInfoDataProvider;
    }

}