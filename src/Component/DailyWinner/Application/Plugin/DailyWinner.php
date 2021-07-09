<?php declare(strict_types=1);

namespace App\Component\DailyWinner\Application\Plugin;

use App\Component\Ranking\Application\Plugin\InformationInterface;
use App\Component\Ranking\Domain\RedisDto;
use App\Component\Ranking\Domain\RedisDtoList;
use App\DataTransferObject\DailyWinnerDataProvider;
use App\DataTransferObject\DailyWinnerListDataProvider;
use App\DataTransferObject\RankingAllEventDataProvider;
use App\Service\RedisKey\RedisKeyService;

class DailyWinner implements InformationInterface
{
    public function get(RankingAllEventDataProvider $rankingAllEvent, RedisDtoList $redisDtoList): RedisDtoList
    {
        $dailyWinnerDataProviderList = new DailyWinnerListDataProvider();

        $users = $rankingAllEvent->getData()->getUsers();

        $dayToUserScore = $this->getInfoDayToUserScore($users);

        foreach ($dayToUserScore as $date => $userToScore) {
            $maxScore = max($userToScore);

            if($maxScore > 0) {
                $dailyWinnerDataProvider = new DailyWinnerDataProvider();
                $dailyWinnerDataProvider->setPoints($maxScore);
                $dailyWinnerDataProvider->setMatchDate($date);
                $users = [];

                foreach ($userToScore as $user => $points) {
                    if ($points === $maxScore) {
                        $users[] = $user;
                    }
                }

                $dailyWinnerDataProvider->setUsers($users);
                $dailyWinnerDataProviderList->addDailyWinner($dailyWinnerDataProvider);
            }
        }

        $redisDtoList->addRedisDto(new RedisDto(RedisKeyService::getDailyWinner(), $dailyWinnerDataProviderList));

        return $redisDtoList;
    }

    private function getDateFromMatchId(string $matchId): string
    {
        return substr(
            $matchId,
            0,
            strpos($matchId,':')
        );
    }

    /**
     * @param \App\DataTransferObject\UserInfoEventDataProvider[] $users
     *
     * @return array
     */
    private function getInfoDayToUserScore(array $users): array
    {
        $data = [];
        foreach ($users as $user) {
            $username = $user->getName();

            foreach ($user->getTips() as $tip) {
                $date = $this->getDateFromMatchId($tip->getMatchId());

                if (!isset($data[$date][$username])) {
                    $data[$date][$username] = 0;
                }

                $data[$date][$username] += $tip->getScore();
            }
        }

        return $data;
    }

}
