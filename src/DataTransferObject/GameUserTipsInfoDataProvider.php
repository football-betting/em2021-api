<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class GameUserTipsInfoDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var string */
    protected $matchId;

    /** @var string */
    protected $team1;

    /** @var string */
    protected $team2;

    /** @var int */
    protected $scoreTeam1;

    /** @var int */
    protected $scoreTeam2;

    /** @var \App\DataTransferObject\UserTipDataProvider[] */
    protected $usersTip = [];


    /**
     * @return string
     */
    public function getMatchId(): string
    {
        return $this->matchId;
    }


    /**
     * @param string $matchId
     * @return GameUserTipsInfoDataProvider
     */
    public function setMatchId(string $matchId)
    {
        $this->matchId = $matchId;

        return $this;
    }


    /**
     * @return GameUserTipsInfoDataProvider
     */
    public function unsetMatchId()
    {
        $this->matchId = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasMatchId()
    {
        return ($this->matchId !== null && $this->matchId !== []);
    }


    /**
     * @return string
     */
    public function getTeam1(): string
    {
        return $this->team1;
    }


    /**
     * @param string $team1
     * @return GameUserTipsInfoDataProvider
     */
    public function setTeam1(string $team1)
    {
        $this->team1 = $team1;

        return $this;
    }


    /**
     * @return GameUserTipsInfoDataProvider
     */
    public function unsetTeam1()
    {
        $this->team1 = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasTeam1()
    {
        return ($this->team1 !== null && $this->team1 !== []);
    }


    /**
     * @return string
     */
    public function getTeam2(): string
    {
        return $this->team2;
    }


    /**
     * @param string $team2
     * @return GameUserTipsInfoDataProvider
     */
    public function setTeam2(string $team2)
    {
        $this->team2 = $team2;

        return $this;
    }


    /**
     * @return GameUserTipsInfoDataProvider
     */
    public function unsetTeam2()
    {
        $this->team2 = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasTeam2()
    {
        return ($this->team2 !== null && $this->team2 !== []);
    }


    /**
     * @return int
     */
    public function getScoreTeam1(): ?int
    {
        return $this->scoreTeam1;
    }


    /**
     * @param int $scoreTeam1
     * @return GameUserTipsInfoDataProvider
     */
    public function setScoreTeam1(?int $scoreTeam1 = null)
    {
        $this->scoreTeam1 = $scoreTeam1;

        return $this;
    }


    /**
     * @return GameUserTipsInfoDataProvider
     */
    public function unsetScoreTeam1()
    {
        $this->scoreTeam1 = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasScoreTeam1()
    {
        return ($this->scoreTeam1 !== null && $this->scoreTeam1 !== []);
    }


    /**
     * @return int
     */
    public function getScoreTeam2(): ?int
    {
        return $this->scoreTeam2;
    }


    /**
     * @param int $scoreTeam2
     * @return GameUserTipsInfoDataProvider
     */
    public function setScoreTeam2(?int $scoreTeam2 = null)
    {
        $this->scoreTeam2 = $scoreTeam2;

        return $this;
    }


    /**
     * @return GameUserTipsInfoDataProvider
     */
    public function unsetScoreTeam2()
    {
        $this->scoreTeam2 = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasScoreTeam2()
    {
        return ($this->scoreTeam2 !== null && $this->scoreTeam2 !== []);
    }


    /**
     * @return \App\DataTransferObject\UserTipDataProvider[]
     */
    public function getUsersTip(): array
    {
        return $this->usersTip;
    }


    /**
     * @param \App\DataTransferObject\UserTipDataProvider[] $usersTip
     * @return GameUserTipsInfoDataProvider
     */
    public function setUsersTip(array $usersTip)
    {
        $this->usersTip = $usersTip;

        return $this;
    }


    /**
     * @return GameUserTipsInfoDataProvider
     */
    public function unsetUsersTip()
    {
        $this->usersTip = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasUsersTip()
    {
        return ($this->usersTip !== null && $this->usersTip !== []);
    }


    /**
     * @param \App\DataTransferObject\UserTipDataProvider $UserTip
     * @return GameUserTipsInfoDataProvider
     */
    public function addUserTip(UserTipDataProvider $UserTip)
    {
        $this->usersTip[] = $UserTip; return $this;
    }


    /**
     * @return array
     */
    protected function getElements(): array
    {
        return array (
          'matchId' =>
          array (
            'name' => 'matchId',
            'allownull' => false,
            'default' => '',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'team1' =>
          array (
            'name' => 'team1',
            'allownull' => false,
            'default' => '',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'team2' =>
          array (
            'name' => 'team2',
            'allownull' => false,
            'default' => '',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'scoreTeam1' =>
          array (
            'name' => 'scoreTeam1',
            'allownull' => true,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'scoreTeam2' =>
          array (
            'name' => 'scoreTeam2',
            'allownull' => true,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'usersTip' =>
          array (
            'name' => 'usersTip',
            'allownull' => false,
            'default' => '',
            'type' => '\\App\\DataTransferObject\\UserTipDataProvider[]',
            'is_collection' => true,
            'is_dataprovider' => false,
            'isCamelCase' => false,
            'singleton' => 'UserTip',
            'singleton_type' => '\\App\\DataTransferObject\\UserTipDataProvider',
          ),
        );
    }
}
