<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class UserRatingListDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var \App\DataTransferObject\UserRatingDataProvider[] */
    protected $users = [];


    /**
     * @return \App\DataTransferObject\UserRatingDataProvider[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }


    /**
     * @param \App\DataTransferObject\UserRatingDataProvider[] $users
     * @return UserRatingListDataProvider
     */
    public function setUsers(array $users)
    {
        $this->users = $users;

        return $this;
    }


    /**
     * @return UserRatingListDataProvider
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
     * @param \App\DataTransferObject\UserRatingDataProvider $User
     * @return UserRatingListDataProvider
     */
    public function addUser(UserRatingDataProvider $User)
    {
        $this->users[] = $User; return $this;
    }


    /**
     * @return array
     */
    protected function getElements(): array
    {
        return array (
          'users' =>
          array (
            'name' => 'users',
            'allownull' => false,
            'default' => '',
            'type' => '\\App\\DataTransferObject\\UserRatingDataProvider[]',
            'is_collection' => true,
            'is_dataprovider' => false,
            'isCamelCase' => false,
            'singleton' => 'User',
            'singleton_type' => '\\App\\DataTransferObject\\UserRatingDataProvider',
          ),
        );
    }
}
