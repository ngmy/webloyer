<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Common\App\Service\ApplicationService;
use InvalidArgumentException;
use Webloyer\App\DataTransformer\User\{
    UserDataTransformer,
    UsersDataTransformer,
};
use Webloyer\Domain\Model\User\{
    User,
    UserId,
    UserRepository,
};

abstract class UserService implements ApplicationService
{
    /** @var UserRepository */
    protected $userRepository;
    /** @var UserDataTransformer */
    protected $userDataTransformer;
    /** @var UsersDataTransformer */
    protected $usersDataTransformer;

    /**
     * @param UserRepository       $userRepository
     * @param UserDataTransformer  $userDataTransformer
     * @param UsersDataTransformer $usersDataTransformer
     * @return void
     */
    public function __construct(
        UserRepository $userRepository,
        UserDataTransformer $userDataTransformer,
        UsersDataTransformer $usersDataTransformer
    ) {
        $this->userRepository = $userRepository;
        $this->userDataTransformer = $userDataTransformer;
        $this->usersDataTransformer = $usersDataTransformer;
    }

    /**
     * @return UserDataTransformer
     */
    public function userDataTransformer(): UserDataTransformer
    {
        return $this->userDataTransformer;
    }

    /**
     * @return UsersDataTransformer
     */
    public function usersDataTransformer(): UsersDataTransformer
    {
        return $this->usersDataTransformer;
    }

    /**
     * @param UserId $id
     * @return User
     * @throws InvalidArgumentException
     */
    protected function getNonNullUser(UserId $id): User
    {
        $user = $this->userRepository->findById($id);
        if (is_null($user)) {
            throw new InvalidArgumentException(
                'User does not exists.' . PHP_EOL .
                'Id: ' . $id->value()
            );
        }
        return $user;
    }
}
