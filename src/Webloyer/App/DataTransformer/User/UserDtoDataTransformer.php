<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\User;

use Webloyer\Domain\Model\User\{
    User,
    UserInterest,
};

class UserDtoDataTransformer implements UserDataTransformer
{
    private $user;

    /**
     * @param User $user
     * @return self
     */
    public function write(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return object
     */
    public function read()
    {
        $dto = new class implements UserInterest {
            public function informEmail(string $email): void
            {
                $this->email = $email;
            }
            public function informName(string $name): void
            {
                $this->name = $name;
            }
            public function informPassword(string $password): void
            {
                $this->passworkd = $password;
            }
            public function informApiToken(string $apiToken): void
            {
                $this->apiToken = $apiToken;
            }
            public function informRoles(array $roles): void
            {
                $this->role = $roles;
            }
        };
        $this->user->provide($dto);

        $dto->surrogateId = $this->recipe->surrogateId();
        $dto->createdAt = $this->recipe->createdAt();
        $dto->updatedAt = $this->recipe->updatedAt();

        return $dto;
    }
}
