<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class DailyWinnerListDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var \App\DataTransferObject\DailyWinnerDataProvider[] */
    protected $data = [];


    /**
     * @return \App\DataTransferObject\DailyWinnerDataProvider[]
     */
    public function getData(): array
    {
        return $this->data;
    }


    /**
     * @param \App\DataTransferObject\DailyWinnerDataProvider[] $data
     * @return DailyWinnerListDataProvider
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }


    /**
     * @return DailyWinnerListDataProvider
     */
    public function unsetData()
    {
        $this->data = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasData()
    {
        return ($this->data !== null && $this->data !== []);
    }


    /**
     * @param \App\DataTransferObject\DailyWinnerDataProvider $DailyWinner
     * @return DailyWinnerListDataProvider
     */
    public function addDailyWinner(DailyWinnerDataProvider $DailyWinner)
    {
        $this->data[] = $DailyWinner; return $this;
    }


    /**
     * @return array
     */
    protected function getElements(): array
    {
        return array (
          'data' =>
          array (
            'name' => 'data',
            'allownull' => false,
            'default' => '',
            'type' => '\\App\\DataTransferObject\\DailyWinnerDataProvider[]',
            'is_collection' => true,
            'is_dataprovider' => false,
            'isCamelCase' => false,
            'singleton' => 'DailyWinner',
            'singleton_type' => '\\App\\DataTransferObject\\DailyWinnerDataProvider',
          ),
        );
    }
}
