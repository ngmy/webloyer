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
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function userFrom(UserId $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }
}
