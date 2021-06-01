<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class TipInfoListDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var string */
    protected $matchId;

    /** @var string */
    protected $team1;

    /** @var string */
    protected $team2;

    /** @var string */
    protected $matchDatetime;

    /** @var int */
    protected $scoreTeam1;

    /** @var int */
    protected $scoreTeam2;

    /** @var \App\DataTransferObject\UserTipDataProvider[] */
    protected $userTips = [];


    /**
     * @return string
     */
    public function getMatchId(): string
    {
        return $this->matchId;
    }


    /**
     * @param string $matchId
     * @return TipInfoListDataProvider
     */
    public function setMatchId(string $matchId)
    {
        $this->matchId = $matchId;

        return $this;
    }


    /**
     * @return TipInfoListDataProvider
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
     * @return TipInfoListDataProvider
     */
    public function setTeam1(string $team1)
    {
        $this->team1 = $team1;

        return $this;
    }


    /**
     * @return TipInfoListDataProvider
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
     * @return TipInfoListDataProvider
     */
    public function setTeam2(string $team2)
    {
        $this->team2 = $team2;

        return $this;
    }


    /**
     * @return TipInfoListDataProvider
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
     * @return string
     */
    public function getMatchDatetime(): string
    {
        return $this->matchDatetime;
    }


    /**
     * @param string $matchDatetime
     * @return TipInfoListDataProvider
     */
    public function setMatchDatetime(string $matchDatetime)
    {
        $this->matchDatetime = $matchDatetime;

        return $this;
    }


    /**
     * @return TipInfoListDataProvider
     */
    public function unsetMatchDatetime()
    {
        $this->matchDatetime = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasMatchDatetime()
    {
        return ($this->matchDatetime !== null && $this->matchDatetime !== []);
    }


    /**
     * @return int
     */
    public function getScoreTeam1(): int
    {
        return $this->scoreTeam1;
    }


    /**
     * @param int $scoreTeam1
     * @return TipInfoListDataProvider
     */
    public function setScoreTeam1(int $scoreTeam1)
    {
        $this->scoreTeam1 = $scoreTeam1;

        return $this;
    }


    /**
     * @return TipInfoListDataProvider
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
    public function getScoreTeam2(): int
    {
        return $this->scoreTeam2;
    }


    /**
     * @param int $scoreTeam2
     * @return TipInfoListDataProvider
     */
    public function setScoreTeam2(int $scoreTeam2)
    {
        $this->scoreTeam2 = $scoreTeam2;

        return $this;
    }


    /**
     * @return TipInfoListDataProvider
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
    public function getUserTips(): array
    {
        return $this->userTips;
    }


    /**
     * @param \App\DataTransferObject\UserTipDataProvider[] $userTips
     * @return TipInfoListDataProvider
     */
    public function setUserTips(array $userTips)
    {
        $this->userTips = $userTips;

        return $this;
    }


    /**
     * @return TipInfoListDataProvider
     */
    public function unsetUserTips()
    {
        $this->userTips = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasUserTips()
    {
        return ($this->userTips !== null && $this->userTips !== []);
    }


    /**
     * @param \App\DataTransferObject\UserTipDataProvider $UserTip
     * @return TipInfoListDataProvider
     */
    public function addUserTip(UserTipDataProvider $UserTip)
    {
        $this->userTips[] = $UserTip; return $this;
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
          'matchDatetime' =>
          array (
            'name' => 'matchDatetime',
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
            'allownull' => false,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'scoreTeam2' =>
          array (
            'name' => 'scoreTeam2',
            'allownull' => false,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'userTips' =>
          array (
            'name' => 'userTips',
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
