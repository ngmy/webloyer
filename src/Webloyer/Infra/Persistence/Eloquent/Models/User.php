<?php

declare(strict_types=1);

namespace Webloyer\Infra\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kodeine\Acl\Traits\HasRole;
use Webloyer\Domain\Model\User\{
    User as UserEntity,
    UserInterest,
};
use Webloyer\Infra\Persistence\Eloquent\ImmutableTimestampable;

/**
 * Webloyer\Infra\Persistence\Eloquent\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \CarbonImmutable $created_at
 * @property \CarbonImmutable $updated_at
 * @property string|null $api_token
 * @property string $uuid
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Kodeine\Acl\Models\Eloquent\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Kodeine\Acl\Models\Eloquent\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\User ofId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\User role($role, $column = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\User whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\User whereUuid($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements UserInterest
{
    use Notifiable;
    use HasRole;
    use ImmutableTimestampable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param Builder $query
     * @param string  $id
     * @return Builder
     */
    public function scopeOfId(Builder $query, string $id): Builder
    {
        return $query->where('uuid', $id);
    }

    /**
     * @param string $id
     * @return void
     */
    public function informId(string $id): void
    {
        $this->uuid = $id;
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
        $this->api_token = $apiToken;
    }

    /**
     * @param array<int, string> $roles
     * @return void
     */
    public function informRoles(array $roles): void
    {
        self::saved(function (User $user) use ($roles) {
            $this->revokeAllRoles();
            $this->assignRole($roles);
        });
    }

    /**
     * @return UserEntity
     */
    public function toEntity(): UserEntity
    {
        return UserEntity::ofWithRole(
            $this->uuid,
            $this->email,
            $this->name,
            $this->password,
            $this->api_token,
            $this->getRoles()
        )
        ->setSurrogateId($this->id)
        ->setCreatedAt($this->created_at)
        ->setUpdatedAt($this->updated_at);
    }
}
