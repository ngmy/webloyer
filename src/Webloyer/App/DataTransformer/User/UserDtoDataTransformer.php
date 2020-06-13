<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\User;

use Webloyer\Domain\Model\User\{
    User,
    UserInterest,
};

class UserDtoDataTransformer implements UserDataTransformer
{
    /** @var User */
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
            /** @var string */
            public $id;
            /** @var string */
            public $email;
            /** @var string */
            public $name;
            /** @var string */
            public $password;
            /** @var string */
            public $apiToken;
            /** @var array<int, string> */
            public $roles;
            /** @var int */
            public $surrogateId;
            /** @var string */
            public $createdAt;
            /** @var string */
            public $updatedAt;
            /**
             * @param string $id
             * @return void
             */
            public function informId(string $id): void
            {
                $this->id = $id;
            }
            /**
             * @param string $email
             * @return void
             */
            public function informEmail(string $email): void
            {
                $this->email = $email;
            }
            /**
             * @param string $name
             * @return void
             */
            public function informName(string $name): void
            {
                $this->name = $name;
            }
            /**
             * @param string $password
             * @return void
             */
            public function informPassword(string $password): void
            {
                $this->password = $password;
            }
            /**
             * @param string $apiToken
             * @return void
             */
            public function informApiToken(string $apiToken): void
            {
                $this->apiToken = $apiToken;
            }
            /**
             * @param array<int, string> $roles
             * @return void
             */
            public function informRoles(array $roles): void
            {
                $this->roles = $roles;
            }
        };
        $this->user->provide($dto);

        $dto->surrogateId = $this->user->surrogateId();
        assert(!is_null($this->user->createdAt()));
        $dto->createdAt = $this->user->createdAt();
        assert(!is_null($this->user->updatedAt()));
        $dto->updatedAt = $this->user->updatedAt();

        return $dto;
    }
}
