<?php declare(strict_types=1);

namespace App\Component\Ranking\Application;

use App\Component\Ranking\Application\Plugin\InformationInterface;
use App\Component\Ranking\Domain\RedisDtoList;
use App\DataTransferObject\RankingAllEventDataProvider;

final class InformationPreparer
{

    /**
     * @var \App\Component\Ranking\Application\Plugin\InformationInterface[]
     */
    private array $collection;

    /**
     * @param \App\Component\Ranking\Application\Plugin\InformationInterface ...$collection
     */
    public function __construct(InformationInterface...$collection)
    {
        $this->collection = $collection;
    }


    public function get(RankingAllEventDataProvider $rankingAllEvent): RedisDtoList
    {
        $redisDtoList = new RedisDtoList();

        foreach ($this->collection as $collection) {
            $redisDtoList = $collection->get($rankingAllEvent, $redisDtoList);
        }

        return $redisDtoList;
    }
}
