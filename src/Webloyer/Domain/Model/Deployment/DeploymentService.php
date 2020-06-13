<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use Webloyer\Domain\Model\User\{
    UserRepository,
    UserId,
    User,
};

class DeploymentService
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserId $userId
     * @return User|null
     */
    public function userFrom(UserId $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }
}
