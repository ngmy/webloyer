<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model\User;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\ConcurrencySafeTrait;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\AbstractEntity;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\Authenticatable;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\HasPermission;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\HasRole;

class User extends AbstractEntity implements AuthenticatableContract
{
    use Authenticatable, HasPermission, HasRole;

    use ConcurrencySafeTrait;

    private $userId;

    private $name;

    private $email;

    private $password;

    private $apiToken;

    private $roleIds = [];

    private $createdAt;

    private $updatedAt;

    public function __construct(UserId $userId, $name, $email, $password, $apiToken, array $roleIds, Carbon $createdAt = null, Carbon $updatedAt = null)
    {
        $this->setUserId($userId);
        $this->setName($name);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setApiToken($apiToken);
        array_walk($roleIds, [$this, 'addRoleId']);
        $this->setCreatedAt($createdAt);
        $this->setUpdatedAt($updatedAt);
        $this->setConcurrencyVersion(md5(serialize($this)));
    }

    public function userId()
    {
        return $this->userId;
    }

    public function name()
    {
        return $this->name;
    }

    public function email()
    {
        return $this->email;
    }

    public function password()
    {
        return $this->password;
    }

    public function apiToken()
    {
        return $this->apiToken;
    }

    public function roleIds()
    {
        return $this->roleIds;
    }

    public function createdAt()
    {
        return $this->createdAt;
    }

    public function updatedAt()
    {
        return $this->updatedAt;
    }

    public function hasRoleId(RoleId $roleId)
    {
        $hasRoleId = false;

        foreach ($this->roleIds() as $roleId) {
            if ($roleId->equals($roleId)) {
                $hasRoleId = true;
                break;
            }
        }

        return $hasRoleId;
    }

    public function equals($object)
    {
        $equalObjects = false;

        if (!is_null($object) && $object instanceof self) {
            $equalObjects = $this->userId()->equals($object->userId());
        }

        return $equalObjects;
    }

    private function setUserId(UserId $userId)
    {
        $this->userId = $userId;

        return $this;
    }

    private function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    private function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    private function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    private function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    private function addRoleId(RoleId $roleId)
    {
        $this->roleIds[] = $roleId;

        return $this;
    }

    private function setCreatedAt(Carbon $createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    private function setUpdatedAt(Carbon $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
