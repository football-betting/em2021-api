<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class UserInfoEventDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var string */
    protected $name;

    /** @var int */
    protected $position;

    /** @var int */
    protected $scoreSum;

    /** @var int */
    protected $sumWinExact;

    /** @var int */
    protected $sumScoreDiff;

    /** @var int */
    protected $sumTeam;

    /** @var int */
    protected $extraPoint;

    /** @var string */
    protected $winner;

    /** @var string */
    protected $winnerSecret;

    /** @var \App\DataTransferObject\TipInfoEventDataProvider[] */
    protected $tips = [];


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @param string $name
     * @return UserInfoEventDataProvider
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return UserInfoEventDataProvider
     */
    public function unsetName()
    {
        $this->name = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasName()
    {
        return ($this->name !== null && $this->name !== []);
    }


    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }


    /**
     * @param int $position
     * @return UserInfoEventDataProvider
     */
    public function setPosition(int $position)
    {
        $this->position = $position;

        return $this;
    }


    /**
     * @return UserInfoEventDataProvider
     */
    public function unsetPosition()
    {
        $this->position = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasPosition()
    {
        return ($this->position !== null && $this->position !== []);
    }


    /**
     * @return int
     */
    public function getScoreSum(): int
    {
        return $this->scoreSum;
    }


    /**
     * @param int $scoreSum
     * @return UserInfoEventDataProvider
     */
    public function setScoreSum(int $scoreSum)
    {
        $this->scoreSum = $scoreSum;

        return $this;
    }


    /**
     * @return UserInfoEventDataProvider
     */
    public function unsetScoreSum()
    {
        $this->scoreSum = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasScoreSum()
    {
        return ($this->scoreSum !== null && $this->scoreSum !== []);
    }


    /**
     * @return int
     */
    public function getSumWinExact(): int
    {
        return $this->sumWinExact;
    }


    /**
     * @param int $sumWinExact
     * @return UserInfoEventDataProvider
     */
    public function setSumWinExact(int $sumWinExact)
    {
        $this->sumWinExact = $sumWinExact;

        return $this;
    }


    /**
     * @return UserInfoEventDataProvider
     */
    public function unsetSumWinExact()
    {
        $this->sumWinExact = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasSumWinExact()
    {
        return ($this->sumWinExact !== null && $this->sumWinExact !== []);
    }


    /**
     * @return int
     */
    public function getSumScoreDiff(): int
    {
        return $this->sumScoreDiff;
    }


    /**
     * @param int $sumScoreDiff
     * @return UserInfoEventDataProvider
     */
    public function setSumScoreDiff(int $sumScoreDiff)
    {
        $this->sumScoreDiff = $sumScoreDiff;

        return $this;
    }


    /**
     * @return UserInfoEventDataProvider
     */
    public function unsetSumScoreDiff()
    {
        $this->sumScoreDiff = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasSumScoreDiff()
    {
        return ($this->sumScoreDiff !== null && $this->sumScoreDiff !== []);
    }


    /**
     * @return int
     */
    public function getSumTeam(): int
    {
        return $this->sumTeam;
    }


    /**
     * @param int $sumTeam
     * @return UserInfoEventDataProvider
     */
    public function setSumTeam(int $sumTeam)
    {
        $this->sumTeam = $sumTeam;

        return $this;
    }


    /**
     * @return UserInfoEventDataProvider
     */
    public function unsetSumTeam()
    {
        $this->sumTeam = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasSumTeam()
    {
        return ($this->sumTeam !== null && $this->sumTeam !== []);
    }


    /**
     * @return int
     */
    public function getExtraPoint(): int
    {
        return $this->extraPoint;
    }


    /**
     * @param int $extraPoint
     * @return UserInfoEventDataProvider
     */
    public function setExtraPoint(int $extraPoint)
    {
        $this->extraPoint = $extraPoint;

        return $this;
    }


    /**
     * @return UserInfoEventDataProvider
     */
    public function unsetExtraPoint()
    {
        $this->extraPoint = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasExtraPoint()
    {
        return ($this->extraPoint !== null && $this->extraPoint !== []);
    }


    /**
     * @return string
     */
    public function getWinner(): string
    {
        return $this->winner;
    }


    /**
     * @param string $winner
     * @return UserInfoEventDataProvider
     */
    public function setWinner(string $winner)
    {
        $this->winner = $winner;

        return $this;
    }


    /**
     * @return UserInfoEventDataProvider
     */
    public function unsetWinner()
    {
        $this->winner = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasWinner()
    {
        return ($this->winner !== null && $this->winner !== []);
    }


    /**
     * @return string
     */
    public function getWinnerSecret(): string
    {
        return $this->winnerSecret;
    }


    /**
     * @param string $winnerSecret
     * @return UserInfoEventDataProvider
     */
    public function setWinnerSecret(string $winnerSecret)
    {
        $this->winnerSecret = $winnerSecret;

        return $this;
    }


    /**
     * @return UserInfoEventDataProvider
     */
    public function unsetWinnerSecret()
    {
        $this->winnerSecret = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasWinnerSecret()
    {
        return ($this->winnerSecret !== null && $this->winnerSecret !== []);
    }


    /**
     * @return \App\DataTransferObject\TipInfoEventDataProvider[]
     */
    public function getTips(): array
    {
        return $this->tips;
    }


    /**
     * @param \App\DataTransferObject\TipInfoEventDataProvider[] $tips
     * @return UserInfoEventDataProvider
     */
    public function setTips(array $tips)
    {
        $this->tips = $tips;

        return $this;
    }


    /**
     * @return UserInfoEventDataProvider
     */
    public function unsetTips()
    {
        $this->tips = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasTips()
    {
        return ($this->tips !== null && $this->tips !== []);
    }


    /**
     * @param \App\DataTransferObject\TipInfoEventDataProvider $Tip
     * @return UserInfoEventDataProvider
     */
    public function addTip(TipInfoEventDataProvider $Tip)
    {
        $this->tips[] = $Tip; return $this;
    }


    /**
     * @return array
     */
    protected function getElements(): array
    {
        return array (
          'name' =>
          array (
            'name' => 'name',
            'allownull' => false,
            'default' => '',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'position' =>
          array (
            'name' => 'position',
            'allownull' => false,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'scoreSum' =>
          array (
            'name' => 'scoreSum',
            'allownull' => false,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'sumWinExact' =>
          array (
            'name' => 'sumWinExact',
            'allownull' => false,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'sumScoreDiff' =>
          array (
            'name' => 'sumScoreDiff',
            'allownull' => false,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'sumTeam' =>
          array (
            'name' => 'sumTeam',
            'allownull' => false,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'extraPoint' =>
          array (
            'name' => 'extraPoint',
            'allownull' => false,
            'default' => '',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'winner' =>
          array (
            'name' => 'winner',
            'allownull' => false,
            'default' => '',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'winnerSecret' =>
          array (
            'name' => 'winnerSecret',
            'allownull' => false,
            'default' => '',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'tips' =>
          array (
            'name' => 'tips',
            'allownull' => false,
            'default' => '',
            'type' => '\\App\\DataTransferObject\\TipInfoEventDataProvider[]',
            'is_collection' => true,
            'is_dataprovider' => false,
            'isCamelCase' => false,
            'singleton' => 'Tip',
            'singleton_type' => '\\App\\DataTransferObject\\TipInfoEventDataProvider',
          ),
        );
    }
}
