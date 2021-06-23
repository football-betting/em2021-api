<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class GameEventListDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var \App\DataTransferObject\GameEventDataProvider[] */
    protected $games = [];


    /**
     * @return \App\DataTransferObject\GameEventDataProvider[]
     */
    public function getGames(): array
    {
        return $this->games;
    }


    /**
     * @param \App\DataTransferObject\GameEventDataProvider[] $games
     * @return GameEventListDataProvider
     */
    public function setGames(array $games)
    {
        $this->games = $games;

        return $this;
    }


    /**
     * @return GameEventListDataProvider
     */
    public function unsetGames()
    {
        $this->games = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasGames()
    {
        return ($this->games !== null && $this->games !== []);
    }


    /**
     * @param \App\DataTransferObject\GameEventDataProvider $Game
     * @return GameEventListDataProvider
     */
    public function addGame(GameEventDataProvider $Game)
    {
        $this->games[] = $Game; return $this;
    }


    /**
     * @return array
     */
    protected function getElements(): array
    {
        return array (
          'games' =>
          array (
            'name' => 'games',
            'allownull' => false,
            'default' => '',
            'type' => '\\App\\DataTransferObject\\GameEventDataProvider[]',
            'is_collection' => true,
            'is_dataprovider' => false,
            'isCamelCase' => false,
            'singleton' => 'Game',
            'singleton_type' => '\\App\\DataTransferObject\\GameEventDataProvider',
          ),
        );
    }
}
