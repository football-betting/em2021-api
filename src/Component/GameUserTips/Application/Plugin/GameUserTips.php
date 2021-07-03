<?php declare(strict_types=1);

namespace App\Component\GameUserTips\Application\Plugin;

use App\Component\GameUserTips\Application\Plugin\Helper\DateValidatorInterface;
use App\Component\GameUserTips\Application\Plugin\Helper\GameToGameUsersTipMapperInterface;
use App\Component\GameUserTips\Application\Plugin\Helper\TipValidatorInterface;
use App\Component\Ranking\Application\Plugin\InformationInterface;
use App\Component\Ranking\Domain\RedisDto;
use App\Component\Ranking\Domain\RedisDtoList;
use App\DataFixtures\AppFixtures;
use App\DataTransferObject\GameEventDataProvider;
use App\DataTransferObject\GameUserTipsInfoDataProvider;
use App\DataTransferObject\RankingAllEventDataProvider;
use App\DataTransferObject\TipInfoDataProvider;
use App\DataTransferObject\TipInfoEventDataProvider;
use App\Service\Redis\RedisService;
use App\Service\RedisKey\RedisKeyService;

class GameUserTips implements InformationInterface
{
    /**
     * @var \App\Component\GameUserTips\Application\Plugin\Helper\GameToGameUsersTipMapperInterface $tipMapper
     */
    private $tipMapper;

    /**
     * @var \App\Component\GameUserTips\Application\Plugin\Helper\TipValidatorInterface $tipValidator
     */
    private  $tipValidator;

    /**
     * @var \App\Component\GameUserTips\Application\Plugin\Helper\DateValidatorInterface $dateValidator
     */
    private $dateValidator;

    public function __construct(GameToGameUsersTipMapperInterface $tipMapper, TipValidatorInterface $tipValidator, DateValidatorInterface $dateValidator){
        $this->tipMapper =  $tipMapper;
        $this->tipValidator = $tipValidator;
        $this->dateValidator = $dateValidator;
    }

    /**
     * @param \App\DataTransferObject\RankingAllEventDataProvider $rankingAllEvent
     * @param \App\Component\Ranking\Domain\RedisDtoList $redisDtoList
     * @return \App\Component\Ranking\Domain\RedisDtoList
     */
    public function get(RankingAllEventDataProvider $rankingAllEvent, RedisDtoList $redisDtoList): RedisDtoList
    {
        $data = $rankingAllEvent->getData();
        $users = $data->getUsers();
        $games = $data->getGames();

        foreach ($games as $game) {
            $matchId = $game->getMatchId();

            if ($this->dateValidator->isDateValid($matchId)) {

                $gameUsersTipDataProvider = $this->tipMapper->mapGameToGameUsersTip($matchId, $game);

                $userTips = [];

                foreach ($users as $user) {
                    $receivedTip = $this->tipValidator->getTips($user->getTips(), $matchId);
                    if ($receivedTip instanceof TipInfoEventDataProvider) {
                        $userTips [] = $receivedTip;
                    }
                }
                $gameUsersTipDataProvider->setUsersTip($userTips);
                $redisDtoList->addRedisDto(new RedisDto(RedisKeyService::getTable(), $gameUsersTipDataProvider));
            }
        }

        return $redisDtoList;
    }




}