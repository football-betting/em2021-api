<?php declare(strict_types=1);

namespace App\Component\UserTips\Application;

use App\DataTransferObject\TipInfoDataProvider;
use App\DataTransferObject\UserInfoDataProvider;
use App\DataTransferObject\UserInfoEventDataProvider;
use App\Repository\TipsRepository;
use App\Service\Redis\RedisServiceInterface;
use App\Service\RedisKey\RedisKeyService;

final class Tips
{
    private RedisServiceInterface $redisService;
    /**
     * @var \App\Repository\TipsRepository
     */
    private TipsRepository $tipsRepository;

    /**
     * @param \App\Service\Redis\RedisServiceInterface $redisService
     * @param \App\Repository\TipsRepository $tipsRepository
     */
    public function __construct(RedisServiceInterface $redisService, TipsRepository $tipsRepository)
    {
        $this->redisService = $redisService;
        $this->tipsRepository = $tipsRepository;
    }

    /**
     * @param string $userName
     *
     * @throws \Exception
     * @return \App\DataTransferObject\UserInfoEventDataProvider
     */
    public function getPastUserTips(string $userName): UserInfoEventDataProvider
    {
        $userInfoDataProvider = $this->getRedisUserInfo($userName);
        $tips = $userInfoDataProvider->getTips();

        foreach ($tips as $key => $tip) {
            $matchDatetime = $this->formatDate($tip->getMatchId());
            $now = (int)(new \DateTime('now'))->format('YmdHi');
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
//        $userInfoDataProvider = $this->getRedisUserInfo($userName);

        $userInfoDataProvider = new UserInfoDataProvider();
        $userInfoDataProvider->setName($userName);
        $userInfoDataProvider->setPosition(0);
        $userInfoDataProvider->setScoreSum(0);
//        $redisInfo = $this->redisService->get('games');
//        $games = \Safe\json_decode($redisInfo, true);
        $games = json_decode(file_get_contents(__DIR__ . '/games.json'), true);

        $tipsFromDb = $this->tipsRepository->getTipUserTips($userName);

        foreach ($games as $game) {
            $tip = new TipInfoDataProvider();
            $tip->fromArray($game);

            if (isset($tipsFromDb[$tip->getMatchId()]) &&  $tipsFromDb[$tip->getMatchId()] instanceof \App\Entity\Tips) {
                $tip->setTipTeam1($tipsFromDb[$tip->getMatchId()]->getTipTeam1());
                $tip->setTipTeam2($tipsFromDb[$tip->getMatchId()]->getTipTeam2());
            }

            $userInfoDataProvider->addTip($tip);
        }

        $tips = $userInfoDataProvider->getTips();

        foreach ($tips as $key => $tip) {
            $matchDatetime = $this->formatDate($tip->getMatchId());
            $now = (int)(new \DateTime('now'))->format('YmdHi');
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
     * @return \App\DataTransferObject\UserInfoEventDataProvider
     */
    private function getRedisUserInfo($userName): UserInfoEventDataProvider
    {
        $userRedisKey = RedisKeyService::getUserTips($userName);
        $redisInfo = $this->redisService->get($userRedisKey);

        $arrayInfo = json_decode($redisInfo, true);
        if (JSON_ERROR_NONE !== json_last_error() || count($arrayInfo) === 0) {
            $userInfoDataProvider = new UserInfoEventDataProvider();
            $userInfoDataProvider->fromArray($arrayInfo);
            $userInfoDataProvider->setName($userName);
            $userInfoDataProvider->setPosition(0);
            $userInfoDataProvider->setScoreSum(0);


            return $userInfoDataProvider;
        }


        $userInfoDataProvider = new UserInfoEventDataProvider();
        $userInfoDataProvider->fromArray($arrayInfo);

        return $userInfoDataProvider;
    }

    /**
     * @param string $date
     *
     * @throws \Exception
     * @return int
     */
    private function formatDate(string $matchId): int
    {
        $matchId = substr($matchId, 0, -6);
        $matchId = str_replace(':' , ' ', $matchId);
        return (int)(new \DateTime($matchId))->format('YmdHi');
    }
}

