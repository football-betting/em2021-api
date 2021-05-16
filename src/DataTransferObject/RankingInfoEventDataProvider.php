<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class RankingInfoEventDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var \App\DataTransferObject\GameEventDataProvider[] */
    protected $games = [];

    /** @var \App\DataTransferObject\UserInfoEventDataProvider[] */
    protected $users = [];


    /**
     * @return \App\DataTransferObject\GameEventDataProvider[]
     */
    public function getGames(): array
    {
        return $this->games;
    }


    /**
     * @param \App\DataTransferObject\GameEventDataProvider[] $games
     * @return RankingInfoEventDataProvider
     */
    public function setGames(array $games)
    {
        $this->games = $games;

        return $this;
    }


    /**
     * @return RankingInfoEventDataProvider
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
     * @return RankingInfoEventDataProvider
     */
    public function addGame(GameEventDataProvider $Game)
    {
        $this->games[] = $Game; return $this;
    }


    /**
     * @return \App\DataTransferObject\UserInfoEventDataProvider[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }


    /**
     * @param \App\DataTransferObject\UserInfoEventDataProvider[] $users
     * @return RankingInfoEventDataProvider
     */
    public function setUsers(array $users)
    {
        $this->users = $users;

        return $this;
    }


    /**
     * @return RankingInfoEventDataProvider
     */
    public function unsetUsers()
    {
        $this->users = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasUsers()
    {
        return ($this->users !== null && $this->users !== []);
    }


    /**
     * @param \App\DataTransferObject\UserInfoEventDataProvider $User
     * @return RankingInfoEventDataProvider
     */
    public function addUser(UserInfoEventDataProvider $User)
    {
        $this->users[] = $User; return $this;
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
          'users' =>
          array (
            'name' => 'users',
            'allownull' => false,
            'default' => '',
            'type' => '\\App\\DataTransferObject\\UserInfoEventDataProvider[]',
            'is_collection' => true,
            'is_dataprovider' => false,
            'isCamelCase' => false,
            'singleton' => 'User',
            'singleton_type' => '\\App\\DataTransferObject\\UserInfoEventDataProvider',
          ),
        );
    }
}
