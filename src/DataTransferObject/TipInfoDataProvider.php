<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class TipInfoDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var string */
    protected $matchId;

    /** @var string */
    protected $matchDatetime;

    /** @var int */
    protected $tipTeam1;

    /** @var int */
    protected $tipTeam2;

    /** @var int */
    protected $scoreTeam1;

    /** @var int */
    protected $scoreTeam2;

    /** @var string */
    protected $team1;

    /** @var string */
    protected $team2;

    /** @var int */
    protected $score;


    /**
     * @return string
     */
    public function getMatchId(): string
    {
        return $this->matchId;
    }


    /**
     * @param string $matchId
     * @return TipInfoDataProvider
     */
    public function setMatchId(string $matchId)
    {
        $this->matchId = $matchId;

        return $this;
    }


    /**
     * @return TipInfoDataProvider
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
    public function getMatchDatetime(): string
    {
        return $this->matchDatetime;
    }


    /**
     * @param string $matchDatetime
     * @return TipInfoDataProvider
     */
    public function setMatchDatetime(string $matchDatetime)
    {
        $this->matchDatetime = $matchDatetime;

        return $this;
    }


    /**
     * @return TipInfoDataProvider
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
    public function getTipTeam1(): ?int
    {
        return $this->tipTeam1;
    }


    /**
     * @param int $tipTeam1
     * @return TipInfoDataProvider
     */
    public function setTipTeam1(?int $tipTeam1 = null)
    {
        $this->tipTeam1 = $tipTeam1;

        return $this;
    }


    /**
     * @return TipInfoDataProvider
     */
    public function unsetTipTeam1()
    {
        $this->tipTeam1 = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasTipTeam1()
    {
        return ($this->tipTeam1 !== null && $this->tipTeam1 !== []);
    }


    /**
     * @return int
     */
    public function getTipTeam2(): ?int
    {
        return $this->tipTeam2;
    }


    /**
     * @param int $tipTeam2
     * @return TipInfoDataProvider
     */
    public function setTipTeam2(?int $tipTeam2 = null)
    {
        $this->tipTeam2 = $tipTeam2;

        return $this;
    }


    /**
     * @return TipInfoDataProvider
     */
    public function unsetTipTeam2()
    {
        $this->tipTeam2 = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasTipTeam2()
    {
        return ($this->tipTeam2 !== null && $this->tipTeam2 !== []);
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
     * @return TipInfoDataProvider
     */
    public function setScoreTeam1(?int $scoreTeam1 = null)
    {
        $this->scoreTeam1 = $scoreTeam1;

        return $this;
    }


    /**
     * @return TipInfoDataProvider
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
     * @return TipInfoDataProvider
     */
    public function setScoreTeam2(?int $scoreTeam2 = null)
    {
        $this->scoreTeam2 = $scoreTeam2;

        return $this;
    }


    /**
     * @return TipInfoDataProvider
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
     * @return string
     */
    public function getTeam1(): string
    {
        return $this->team1;
    }


    /**
     * @param string $team1
     * @return TipInfoDataProvider
     */
    public function setTeam1(string $team1)
    {
        $this->team1 = $team1;

        return $this;
    }


    /**
     * @return TipInfoDataProvider
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
     * @return TipInfoDataProvider
     */
    public function setTeam2(string $team2)
    {
        $this->team2 = $team2;

        return $this;
    }


    /**
     * @return TipInfoDataProvider
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
    public function getScore(): ?int
    {
        return $this->score;
    }


    /**
     * @param int $score
     * @return TipInfoDataProvider
     */
    public function setScore(?int $score = null)
    {
        $this->score = $score;

        return $this;
    }


    /**
     * @return TipInfoDataProvider
     */
    public function unsetScore()
    {
        $this->score = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasScore()
    {
        return ($this->score !== null && $this->score !== []);
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
          'tipTeam1' =>
          array (
            'name' => 'tipTeam1',
            'allownull' => true,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'tipTeam2' =>
          array (
            'name' => 'tipTeam2',
            'allownull' => true,
            'default' => '',
            'type' => 'int',
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
          'score' =>
          array (
            'name' => 'score',
            'allownull' => true,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
        );
    }
}
