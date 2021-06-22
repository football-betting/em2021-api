<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class UserTipDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var string */
    protected $name;

    /** @var int */
    protected $score;

    /** @var int */
    protected $tipTeam1;

    /** @var int */
    protected $tipTeam2;


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @param string $name
     * @return UserTipDataProvider
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return UserTipDataProvider
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
    public function getScore(): ?int
    {
        return $this->score;
    }


    /**
     * @param int $score
     * @return UserTipDataProvider
     */
    public function setScore(?int $score = null)
    {
        $this->score = $score;

        return $this;
    }


    /**
     * @return UserTipDataProvider
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
     * @return int
     */
    public function getTipTeam1(): ?int
    {
        return $this->tipTeam1;
    }


    /**
     * @param int $tipTeam1
     * @return UserTipDataProvider
     */
    public function setTipTeam1(?int $tipTeam1 = null)
    {
        $this->tipTeam1 = $tipTeam1;

        return $this;
    }


    /**
     * @return UserTipDataProvider
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
     * @return UserTipDataProvider
     */
    public function setTipTeam2(?int $tipTeam2 = null)
    {
        $this->tipTeam2 = $tipTeam2;

        return $this;
    }


    /**
     * @return UserTipDataProvider
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
        );
    }
}
