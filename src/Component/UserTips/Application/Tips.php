<?php declare(strict_types=1);

namespace App\Component\UserTips\Application;

use App\DataTransferObject\MatchDetailDataProvider;
use App\DataTransferObject\MatchListDataProvider;
use App\DataTransferObject\TipEventDataProvider;
use App\DataTransferObject\TipInfoDataProvider;
use App\DataTransferObject\UserInfoDataProvider;
use App\Repository\TipsRepository;
use App\Service\Redis\RedisServiceInterface;
use App\Service\RedisKey\RedisKeyService;
use RuntimeException;

final class Tips
{
    private RedisServiceInterface $redisService;
    /**
     * @var \App\Repository\TipsRepository
     */
    private TipsRepository $tipsRepository;

    /**
     * @param \App\Service\Redis\RedisServiceInterface $redisService
     */
    public function __construct(RedisServiceInterface $redisService, TipsRepository $tipsRepository)
    {
        $this->redisService = $redisService;
        $this->tipsRepository = $tipsRepository;
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
        //$userInfoDataProvider = $this->getRedisUserInfo($userName);
        $userInfoDataProvider = new UserInfoDataProvider();
        $userInfoDataProvider->setName($userName);
        $userInfoDataProvider->setPosition(0);
        $userInfoDataProvider->setScoreSum(0);

        $tipsFromDb = $this->tipsRepository->getTipUserTips($userName);

        $games = json_decode(file_get_contents(__DIR__ . '/games.json'), true);
        $matchIdsAlreadyPlayed = array_keys($tipsFromDb);

        foreach ($games as $game) {
            $tip = new TipInfoDataProvider();
            $tip->fromArray($game);

            if (in_array($tip->getMatchId(), $matchIdsAlreadyPlayed)) {
                if (isset($tipsFromDb[$tip->getMatchId()]) &&  $tipsFromDb[$tip->getMatchId()] instanceof \App\Entity\Tips) {
                    $tip->setTipTeam1($tipsFromDb[$tip->getMatchId()]->getTipTeam1());
                    $tip->setTipTeam2($tipsFromDb[$tip->getMatchId()]->getTipTeam2());
                }

                $userInfoDataProvider->addTip($tip);
            }
        }

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
        if (JSON_ERROR_NONE !== json_last_error() || count($arrayInfo) === 0) {
            $userInfoDataProvider = new UserInfoDataProvider();
            $userInfoDataProvider->setName($userName);
            $userInfoDataProvider->setPosition(0);
            $userInfoDataProvider->setScoreSum(0);
            $redisInfo = $this->redisService->get('games');

            $games = \Safe\json_decode($redisInfo, true);

            foreach ($games as $game) {
                $tip = new TipInfoDataProvider();
                $tip->fromArray($game);

                $userInfoDataProvider->addTip($tip);
            }

            return $userInfoDataProvider;
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

