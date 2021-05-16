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

    /** @var \App\DataTransferObject\TipEventDataProvider[] */
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
     * @return \App\DataTransferObject\TipEventDataProvider[]
     */
    public function getTips(): array
    {
        return $this->tips;
    }


    /**
     * @param \App\DataTransferObject\TipEventDataProvider[] $tips
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
     * @param \App\DataTransferObject\TipEventDataProvider $Tip
     * @return UserInfoEventDataProvider
     */
    public function addTip(TipEventDataProvider $Tip)
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
          'tips' =>
          array (
            'name' => 'tips',
            'allownull' => false,
            'default' => '',
            'type' => '\\App\\DataTransferObject\\TipEventDataProvider[]',
            'is_collection' => true,
            'is_dataprovider' => false,
            'isCamelCase' => false,
            'singleton' => 'Tip',
            'singleton_type' => '\\App\\DataTransferObject\\TipEventDataProvider',
          ),
        );
    }
}
