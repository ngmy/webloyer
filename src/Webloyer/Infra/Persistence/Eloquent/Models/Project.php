<?php

declare(strict_types=1);

namespace Webloyer\Infra\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\{
    Builder,
    Model,
};
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    BelongsToMany,
    HasOne,
};
use InvalidArgumentException;
use Ngmy\EloquentSerializedLob\SerializedLobTrait;
use Webloyer\Domain\Model\Project\{
    Project as ProjectEntity,
    ProjectInterest,
};
use Webloyer\Infra\Persistence\Eloquent\ImmutableTimestampable;

/**
 * Webloyer\Infra\Persistence\Eloquent\Models\Project
 *
 * @property int $id
 * @property string $name
 * @property string $stage
 * @property int $server_id
 * @property string $repository
 * @property string|null $email_notification_recipient
 * @property string $attributes
 * @property int|null $days_to_keep_deployments
 * @property int|null $max_number_of_deployments_to_keep
 * @property int $keep_last_deployment
 * @property string|null $github_webhook_secret
 * @property int|null $github_webhook_user_id
 * @property \CarbonImmutable $created_at
 * @property \CarbonImmutable $updated_at
 * @property string $uuid
 * @property-read \Webloyer\Infra\Persistence\Eloquent\Models\MaxDeployment|null $maxDeployment
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webloyer\Infra\Persistence\Eloquent\Models\Recipe[] $recipes
 * @property-read int|null $recipes_count
 * @property-read \Webloyer\Infra\Persistence\Eloquent\Models\Server $server
 * @property-read \Webloyer\Infra\Persistence\Eloquent\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project ofId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project whereAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project whereDaysToKeepDeployments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project whereEmailNotificationRecipient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project whereGithubWebhookSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project whereGithubWebhookUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project whereKeepLastDeployment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project whereMaxNumberOfDeploymentsToKeep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project whereRepository($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project whereStage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Project whereUuid($value)
 * @mixin \Eloquent
 */
class Project extends Model implements ProjectInterest
{
    use ImmutableTimestampable;
    use SerializedLobTrait;

    /** @var list<string> */
    protected $fillable = [
        'name',
        'stage',
        'repository',
        'server_id',
        'email_notification_recipient',
        'attributes',
        'days_to_keep_deployments',
        'max_number_of_deployments_to_keep',
        'keep_last_deployment',
        'github_webhook_secret',
        'github_webhook_user_id',
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
     * @return HasOne
     */
    public function maxDeployment(): HasOne
    {
        return $this->hasOne(MaxDeployment::class);
    }

    /**
     * @return BelongsToMany
     */
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class)
            ->withPivot('recipe_order')
            ->orderBy('recipe_order');
    }

    /**
     * @return BelongsTo
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'github_webhook_user_id');
    }

    /**
     * @param string $id
     * @return void
     * @see ProjectInterest::informId()
     */
    public function informId(string $id): void
    {
        $this->uuid = $id;
    }

    /**
     * @param string $name
     * @return void
     * @see ProjectInterest::informName()
     */
    public function informName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string ...$recipeIds
     * @return void
     * @see ProjectInterest::informRecipeIds()
     */
    public function informRecipeIds(string ...$recipeIds): void
    {
        if (empty($recipeIds)) {
            throw new InvalidArgumentException('Recipe is required.');
        }
        foreach ($recipeIds as $i => $recipeId) {
            $recipeOrm = Recipe::ofId($recipeId)->first();
            if (is_null($recipeOrm)) {
                throw new InvalidArgumentException(
                    'Recipe does not exists.' . PHP_EOL .
                    'Recipe Id: ' . $recipeId
                );
            }
            $syncRecipeIds[$recipeOrm->id] = ['recipe_order' => $i + 1];
        }

        self::saved(function (Project $project) use ($syncRecipeIds) {
            $project->recipes()->sync($syncRecipeIds);
        });
    }

    /**
     * @param string $serverId
     * @return void
     * @see ProjectInterest::informServerId()
     */
    public function informServerId(string $serverId): void
    {
        $serverOrm = Server::ofId($serverId)->first();
        if (is_null($serverOrm)) {
            throw new InvalidArgumentException(
                'Server does not exists.' . PHP_EOL .
                'Server Id: ' . $serverId
            );
        }
        $this->server_id = $serverOrm->id;
    }

    /**
     * @param string $repositoryUrl
     * @return void
     * @see ProjectInterest::informRepositoryUrl()
     */
    public function informRepositoryUrl(string $repositoryUrl): void
    {
        $this->repository = $repositoryUrl;
    }

    /**
     * @param string $stageName
     * @return void
     * @see ProjectInterest::informStageName()
     */
    public function informStageName(string $stageName): void
    {
        $this->stage = $stageName;
    }

    /**
     * @param string|null $deployPath
     * @return void
     * @see ProjectInterest::informDeployPath()
     */
    public function informDeployPath(?string $deployPath): void
    {
        // NOTE "attributes" is reserved word
        $rawAttributes = $this->getAttributeFromArray('attributes');

        // NOTE If the "attributes" column is null,
        //      deserialzie will cause an error, so initialize
        if (is_null($rawAttributes)) {
            $this->setAttribute('attributes', []);
        }

        $attributes = $this->getAttribute('attributes');
        if (is_null($deployPath)) {
            unset($attributes['deploy_path']);
        } else {
            $attributes['deploy_path'] = $deployPath;
        }
        $this->setAttribute('attributes', $attributes);
    }

    /**
     * @param string|null $emailNotificationRecipient
     * @return void
     * @see ProjectInterest::informEmailNotificationRecipient()
     */
    public function informEmailNotificationRecipient(?string $emailNotificationRecipient): void
    {
        $this->email_notification_recipient = $emailNotificationRecipient;
    }

    /**
     * @param int|null $deploymentKeepDays
     * @return void
     * @see ProjectInterest::informDeploymentKeepDays()
     */
    public function informDeploymentKeepDays(?int $deploymentKeepDays): void
    {
        $this->days_to_keep_deployments = $deploymentKeepDays;
    }

    /**
     * @param bool $keepLastDeployment
     * @return void
     * @see ProjectInterest::informKeepLastDeployment()
     */
    public function informKeepLastDeployment(bool $keepLastDeployment): void
    {
        $this->keep_last_deployment = (int) $keepLastDeployment;
    }

    /**
     * @param int|null $deploymentKeepMaxNumber
     * @return void
     * @see ProjectInterest::informDeploymentKeepMaxNumber()
     */
    public function informDeploymentKeepMaxNumber(?int $deploymentKeepMaxNumber): void
    {
        $this->max_number_of_deployments_to_keep = $deploymentKeepMaxNumber;
    }

    /**
     * @param string|null $gitHubWebhookSecret
     * @return void
     * @see ProjectInterest::informGitHubWebhookSecret()
     */
    public function informGitHubWebhookSecret(?string $gitHubWebhookSecret): void
    {
        $this->github_webhook_secret = $gitHubWebhookSecret;
    }

    /**
     * @param string|null $gitHubWebhookExecutor
     * @return void
     * @see ProjectInterest::informGitHubWebhookExecutor()
     */
    public function informGitHubWebhookExecutor(?string $gitHubWebhookExecutor): void
    {
        if (is_null($gitHubWebhookExecutor)) {
            $this->github_webhook_user_id = null;
            return;
        }

        $userOrm = User::ofId($gitHubWebhookExecutor)->first();
        if (is_null($userOrm)) {
            throw new InvalidArgumentException(
                'User does not exists.' . PHP_EOL .
                'User Id: ' . $gitHubWebhookExecutor
            );
        }
        $this->github_webhook_user_id = $userOrm->id;
    }

    /**
     * @return ProjectEntity
     */
    public function toEntity(): ProjectEntity
    {
        return ProjectEntity::of(
            $this->uuid,
            $this->name,
            $this->recipes->map(function (Recipe $recipe) {
                return $recipe->uuid;
            })->toArray(),
            isset($this->server) ? $this->server->uuid : null,
            $this->repository,
            $this->stage,
            $this->getAttribute('attributes')['deploy_path'] ?? null,
            $this->email_notification_recipient,
            $this->days_to_keep_deployments ? (int) $this->days_to_keep_deployments : null,
            (bool) $this->keep_last_deployment,
            $this->max_number_of_deployments_to_keep ? (int) $this->max_number_of_deployments_to_keep : null,
            $this->github_webhook_secret,
            isset($this->user) ? $this->user->uuid : null
        )
        ->setSurrogateId($this->id)
        ->setCreatedAt($this->created_at)
        ->setUpdatedAt($this->updated_at);
    }

    /**
     * @return string
     */
    protected function getSerializationColumn(): string
    {
        return 'attributes';
    }

    /**
     * @return string
     */
    protected function getSerializationType(): string
    {
        return 'json';
    }

    /**
     * @return string
     */
    protected function getDeserializationType(): string
    {
        return 'array';
    }
}
