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
        'name', 'email', 'password', 'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token',
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
     * @param string  $email
     * @return Builder
     */
    public function scopeOfEmail(Builder $query, string $email): Builder
    {
        return $query->where('email', $email);
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
        $this->revokeAllRoles();
        $this->assignRole($roles);
    }

    /**
     * @return UserEntity
     */
    public function toEntity(): UserEntity
    {
        return UserEntity::ofWithRole(
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
