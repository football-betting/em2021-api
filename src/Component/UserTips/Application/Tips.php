<?php declare(strict_types=1);

namespace App\Component\UserTips\Application;

use App\DataTransferObject\GameEventListDataProvider;
use App\DataTransferObject\TipInfoDataProvider;
use App\DataTransferObject\UserInfoDataProvider;
use App\DataTransferObject\UserInfoEventDataProvider;
use App\Entity\User;
use App\Repository\TipsRepository;
use App\Repository\UserRepository;
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
     * @var \App\Repository\UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @param \App\Service\Redis\RedisServiceInterface $redisService
     * @param \App\Repository\TipsRepository $tipsRepository
     */
    public function __construct(
        RedisServiceInterface $redisService,
        TipsRepository $tipsRepository,
        UserRepository $userRepository
    )
    {
        $this->redisService = $redisService;
        $this->tipsRepository = $tipsRepository;
        $this->userRepository = $userRepository;
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
        $userInfoDataProvider = new UserInfoDataProvider();
        $userInfoDataProvider->setName($userName);
        $userInfoDataProvider->setPosition(0);
        $userInfoDataProvider->setScoreSum(0);
        $redisInfo = $this->redisService->get(RedisKeyService::getGames());
        if(empty($redisInfo)) {
            $games = \Safe\json_decode(file_get_contents(__DIR__ . '/games.json'), true);
            usort($games, fn ($a, $b) => strtotime($a["matchDatetime"]) - strtotime($b["matchDatetime"]));
            $games['games'] = $games;
        } else {
            $games = \Safe\json_decode($redisInfo, true);
        }


        $gameEventListDataProvider = new GameEventListDataProvider();
        $gameEventListDataProvider->fromArray($games);

        $tipsFromDb = $this->tipsRepository->getTipUserTips($userName);

        foreach ($gameEventListDataProvider->getGames() as $game) {

            $matchDatetime = $this->formatDate($game->getMatchId());
            $now = (int)(new \DateTime('now'))->format('YmdHi');
            if ($matchDatetime <= $now) {
                continue;
            }

            $tip = new TipInfoDataProvider();

            $tip->setMatchId($game->getMatchId());
            $tip->setTeam1($game->getTeam1());
            $tip->setTeam2($game->getTeam2());
            $tip->setScoreTeam1($game->getScoreTeam1());
            $tip->setScoreTeam2($game->getScoreTeam2());

            $tip->setMatchDatetime($this->formatMatchIdToDate($game->getMatchId()));

            if (isset($tipsFromDb[$tip->getMatchId()]) && $tipsFromDb[$tip->getMatchId()] instanceof \App\Entity\Tips) {
                $tip->setTipTeam1($tipsFromDb[$tip->getMatchId()]->getTipTeam1());
                $tip->setTipTeam2($tipsFromDb[$tip->getMatchId()]->getTipTeam2());
            }

            $userInfoDataProvider->addTip($tip);
        }

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

        if(!$userInfoDataProvider->hasExtraPoint()) {
            $userInfoDataProvider->setExtraPoint(0);
        }

        $winner = '-';
        $winnerSecret = '-';
        $user = $this->userRepository->findOneBy(['username' => $userInfoDataProvider->getName()]);
        if ($user instanceof User) {
            $winner = $user->getTip1();
            $winnerSecret = $user->getTip2();
        }

        $userInfoDataProvider->setWinner($winner);
        $userInfoDataProvider->setWinnerSecret($winnerSecret);

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
        $matchId = str_replace(':', ' ', $matchId);
        return (int)(new \DateTime($matchId))->format('YmdHi');
    }

    /**
     * @param string $matchId
     *
     * @throws \Exception
     * @return string
     */
    private function formatMatchIdToDate(string $matchId): string
    {
        $matchId = substr($matchId, 0, -6);
        $matchId = str_replace(':', ' ', $matchId);
        return (new \DateTime($matchId))->format('Y-m-d H:i');
    }
}

