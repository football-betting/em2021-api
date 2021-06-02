<?php declare(strict_types=1);

namespace App\Component\Ranking\Application\Plugin;

use App\Component\Ranking\Domain\RedisDtoList;
use App\DataTransferObject\RankingAllEventDataProvider;

interface InformationInterface
{
    public function get(RankingAllEventDataProvider $rankingAllEvent, RedisDtoList $redisDtoList): RedisDtoList;
}
