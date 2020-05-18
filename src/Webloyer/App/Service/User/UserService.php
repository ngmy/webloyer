<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Common\App\Service\ApplicationService;
use InvalidArgumentException;
use Webloyer\Domain\Model\User\{
    User,
    UserEmail,
    UserRepository,
};

abstract class UserService implements ApplicationService
{
    /** @var UserRepository */
    protected $userRepository;

    /**
     * @param UserRepository $userRepository
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserEmail $email
     * @return User
     * @throws InvalidArgumentException
     */
    protected function getNonNullUser(UserEmail $email): User
    {
        $user = $this->userRepository->findByEmail($email);
        if (is_null($user)) {
            throw new InvalidArgumentException(
                'User does not exists.' . PHP_EOL .
                'Email: ' . $email->value()
            );
        }
        return $user;
    }
}
