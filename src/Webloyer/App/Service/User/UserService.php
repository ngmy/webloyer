<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use DB;
use InvalidArgumentException;
use Webloyer\App\Service\User\Commands;
use Webloyer\Domain\Model\User;

class UserService
{
    /** @var User\UserRepository */
    private $userRepository;

    /**
     * @param User\UserRepository $userRepository
     * @return void
     */
    public function __construct(User\UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return User\Users
     */
    public function getAllUsers(): User\Users
    {
        return $this->userRepository->findAll();
    }

    /**
     * @param Commands\GetUsersCommand $command
     * @return User\Users
     */
    public function getUsers(Commands\GetUsersCommand $command): User\Users
    {
        return $this->userRepository->findAllByPage($command->getPage(), $command->getPerPage());
    }

    /**
     * @param Commands\GetUserCommand $command
     * @return User\User
     */
    public function getUser(Commands\GetUserCommand $command): User\User
    {
        $id = new User\UserId($command->getId());
        return $this->getNonNullUser($id);
    }

    /**
     * @param Commands\CreateUserCommand $command
     * @return void
     */
    public function createUser(Commands\CreateUserCommand $command): void
    {
        DB::transaction(function () use ($command): void {
            $user = User\User::of(
                $this->userRepository->nextId()->value(),
                $command->getName(),
                $command->getDescription(),
                $command->getBody()
            );
            $this->userRepository->save($user);
        });
    }

    /**
     * @param Commands\UpdateUserCommand $command
     * @return void
     */
    public function updateUser(Commands\UpdateUserCommand $command): void
    {
        DB::transaction(function () use ($command): void {
            $id = new User\UserId($command->getId());
            $name = new User\UserName($command->getName());
            $description = new User\UserDescription($command->getDescription());
            $body = new User\UserBody($command->getBody());
            $user = $this->getNonNullUser($id)
                ->changeName($name)
                ->changeDescription($description)
                ->changeBody($body);
            $this->userRepository->save($user);
        });
    }

    /**
     * @param Commands\DeleteUserCommand $command
     * @return void
     */
    public function deleteUser(Commands\DeleteUserCommand $command): void
    {
        DB::transaction(function () use ($command): void {
            $id = new User\UserId($command->getId());
            $user = $this->getNonNullUser($id);
            $this->userRepository->remove($user);
        });
    }

    /**
     * @param User\UserId $id
     * @return User\User
     * @throws InvalidArgumentException
     */
    private function getNonNullUser(User\UserId $id): User\User
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
