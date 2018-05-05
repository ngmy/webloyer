<?php

namespace Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\Eloquent;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Kodeine\Acl\Traits\HasRole;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User as EntityUser;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\Eloquent\AbstractBaseEloquent;

class User extends AbstractBaseEloquent implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, HasRole;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    /**
     * Convert an Eloquent object into an entity.
     *
     * @return \Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User
     */
    public function toEntity()
    {
        return new EntityUser(
            new UserId($this->id),
            $this->name,
            $this->email,
            $this->password,
            $this->api_token,
            $this->roles->map(function ($role) {
                return new RoleId($role->id);
            })->all(),
            $this->created_at,
            $this->updated_at
        );
    }
}
