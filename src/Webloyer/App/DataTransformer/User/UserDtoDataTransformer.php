<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\User;

use Webloyer\Domain\Model\User\{
    NullUser,
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
            /** @var string|null */
            public $apiToken;
            /** @var list<string> */
            public $roles;
            /** @var int */
            public $surrogateId;
            /** @var string|null */
            public $createdAt;
            /** @var string|null */
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
             * @param string|null $apiToken
             * @return void
             */
            public function informApiToken(?string $apiToken): void
            {
                $this->apiToken = $apiToken;
            }
            /**
             * @param list<string> $roles
             * @return void
             */
            public function informRoles(array $roles): void
            {
                $this->roles = $roles;
            }
        };
        $this->user->provide($dto);

        $dto->surrogateId = $this->user->surrogateId();
        assert(!$this->user instanceof NullUser || is_null($this->user->createdAt())); // If NullUser then createdAt is null
        assert($this->user instanceof NullUser || !is_null($this->user->createdAt())); // If not NullUser then createdAt is not null
        $dto->createdAt = $this->user->createdAt();
        assert(!$this->user instanceof NullUser || is_null($this->user->updatedAt())); // If NullUser then updatedAt is null
        assert($this->user instanceof NullUser || !is_null($this->user->updatedAt())); // If not NullUser then updatedAt is not null
        $dto->updatedAt = $this->user->updatedAt();

        return $dto;
    }
}
