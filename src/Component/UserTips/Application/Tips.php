<?php declare(strict_types=1);

namespace App\Component\UserTips\Application;

use App\DataTransferObject\UserInfoDataProvider;
use App\Service\Redis\RedisServiceInterface;
use App\Service\RedisKey\RedisKeyService;
use RuntimeException;

final class Tips
{
    private RedisServiceInterface $redisService;

    /**
     * @param \App\Service\Redis\RedisServiceInterface $redisService
     */
    public function __construct(RedisServiceInterface $redisService)
    {
        $this->redisService = $redisService;
    }

    /**
     * @param string $userName
     *
     * @return \App\DataTransferObject\UserInfoDataProvider
     */
    public function getUserTips(string $userName): UserInfoDataProvider
    {
        return $this->getRedisUserInfo($userName);
    }

    /**
     * @param string $userName
     *
     * @throws \Exception
     * @return \App\DataTransferObject\UserInfoDataProvider
     */
    public function getPastUserTips(string $userName): UserInfoDataProvider
    {
        $userInfoDataProvider = $this->getRedisUserInfo($userName);
        $tips = $userInfoDataProvider->getTips();

        foreach ($tips as $key => $tip) {
            $matchDatetime = $this->formatDate($tip->getMatchDatetime());
            $now = $this->formatDate('now');
            if ($matchDatetime > $now) {
                unset($tips[$key]);
            }
        }

        $userInfoDataProvider->setTips($tips);

        return $userInfoDataProvider;
    }

    /**
     * @param string $userName
     *
     * @throws \Exception
     * @return \App\DataTransferObject\UserInfoDataProvider
     */
    public function getFutureUserTips(string $userName): UserInfoDataProvider
    {
        $userInfoDataProvider = $this->getRedisUserInfo($userName);
        $tips = $userInfoDataProvider->getTips();

        foreach ($tips as $key => $tip) {
            $matchDatetime = $this->formatDate($tip->getMatchDatetime());
            $now = $this->formatDate('now');
            if ($matchDatetime <= $now) {
                unset($tips[$key]);
            }
        }

        $userInfoDataProvider->setTips(array_values($tips));

        return $userInfoDataProvider;
    }

    /**
     * @param $userName
     *
     * @return \App\DataTransferObject\UserInfoDataProvider
     */
    private function getRedisUserInfo($userName): UserInfoDataProvider
    {
        $userRedisKey = RedisKeyService::getUserTips($userName);
        $redisInfo = $this->redisService->get($userRedisKey);

        $arrayInfo = json_decode($redisInfo, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException(\json_last_error_msg(), \json_last_error());
        }

        if (count($arrayInfo) === 0) {
            throw new RuntimeException('Empty data');
        }

        $userInfoDataProvider = new UserInfoDataProvider();
        $userInfoDataProvider->fromArray($arrayInfo);

        return $userInfoDataProvider;
    }

    /**
     * @param string $date
     *
     * @throws \Exception
     * @return int
     */
    private function formatDate(string $date): int
    {
        return (int)(new \DateTime($date))->format('YmdHi');
    }
}
