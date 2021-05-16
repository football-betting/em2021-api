<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class RankingAllEventDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var string */
    protected $event;

    /** @var \App\DataTransferObject\RankingInfoEventDataProvider */
    protected $data;


    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }


    /**
     * @param string $event
     * @return RankingAllEventDataProvider
     */
    public function setEvent(string $event)
    {
        $this->event = $event;

        return $this;
    }


    /**
     * @return RankingAllEventDataProvider
     */
    public function unsetEvent()
    {
        $this->event = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasEvent()
    {
        return ($this->event !== null && $this->event !== []);
    }


    /**
     * @return \App\DataTransferObject\RankingInfoEventDataProvider
     */
    public function getData(): RankingInfoEventDataProvider
    {
        return $this->data;
    }


    /**
     * @param \App\DataTransferObject\RankingInfoEventDataProvider $data
     * @return RankingAllEventDataProvider
     */
    public function setData(RankingInfoEventDataProvider $data)
    {
        $this->data = $data;

        return $this;
    }


    /**
     * @return RankingAllEventDataProvider
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
     * @return array
     */
    protected function getElements(): array
    {
        return array (
          'event' =>
          array (
            'name' => 'event',
            'allownull' => false,
            'default' => '',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'data' =>
          array (
            'name' => 'data',
            'allownull' => false,
            'default' => '',
            'type' => '\\App\\DataTransferObject\\RankingInfoEventDataProvider',
            'is_collection' => false,
            'is_dataprovider' => true,
            'isCamelCase' => false,
          ),
        );
    }
}
