<?php

declare(strict_types=1);

namespace Webloyer\Infra\Domain\Model\User;

use Common\Domain\Model\Identity\IdGenerator;
use Webloyer\Domain\Model\User\{
    User,
    UserApiToken,
    UserId,
    UserRepository,
    Users,
};
use Webloyer\Infra\Persistence\Eloquent\Models\User as UserOrm;

class EloquentUserRepository implements UserRepository
{
    /** @var IdGenerator */
    private $idGenerator;

    public function __construct(IdGenerator $idGenerator)
    {
        $this->idGenerator = $idGenerator;
    }

    /**
     * @return UserId
     * @see UserRepository::nextId()
     */
    public function nextId(): UserId
    {
        return new UserId($this->idGenerator->generate());
    }

    /**
     * @return Users
     * @see UserRepository::findAll()
     */
    public function findAll(): Users
    {
        $userArray = UserOrm::orderBy('name')
            ->get()
            ->map(function (UserOrm $userOrm): User {
                return $userOrm->toEntity();
            })
            ->toArray();
        return new Users(...$userArray);
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     * @return Users
     * @see UserRepository::findAllByPage()
     */
    public function findAllByPage(?int $page, ?int $perPage): Users
    {
        $page = $page ?? 1;
        $perPage = $perPage ?? 10;

        $userArray = UserOrm::orderBy('name')
            ->skip($perPage * ($page - 1))
            ->take($perPage)
            ->get()
            ->map(function (UserOrm $userOrm): User {
                return $userOrm->toEntity();
            })
            ->toArray();
        return new Users(...$userArray);
    }

    /**
     * @param UserId $id
     * @return User|null
     * @see UserRepository::findById()
     */
    public function findById(UserId $id): ?User
    {
        $userOrm = UserOrm::ofId($id->value())->first();
        if (is_null($userOrm)) {
            return null;
        }
        return $userOrm->toEntity();
    }

    /**
     * @param UserApiToken $apiToken
     * @return User|null
     * @see UserRepository::findByApiToken()
     */
    public function findByApiToken(UserApiToken $apiToken): ?User
    {
        $userOrm = UserOrm::where('api_token', $apiToken->value())->first();
        if (is_null($userOrm)) {
            return null;
        }
        return $userOrm->toEntity();
    }

    /**
     * @param User $user
     * @return void
     * @see UserRepository::remove()
     */
    public function remove(User $user): void
    {
        $userOrm = UserOrm::ofId($user->id())->first();
        if (is_null($userOrm)) {
            return;
        }
        $userOrm->delete();
    }

    /**
     * @param User $user
     * @return void
     * @see UserRepository::save()
     */
    public function save(User $user): void
    {
        $userOrm = UserOrm::firstOrNew(['uuid' => $user->id()]);
        $user->provide($userOrm);
        $userOrm->save();

        $user->setSurrogateId($userOrm->id)
            ->setCreatedAt($userOrm->created_at)
            ->setUpdatedAt($userOrm->updated_at);
    }
}
