<?php
declare(strict_types=1);

namespace App\Models;

use App\Specifications\DeploymentSpecification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Ngmy\EloquentSerializedLob\SerializedLobTrait;
use Illuminate\Support\Collection;
use DateTime;
use App\Jobs\Deploy;

/**
 * Class Project
 * @package App\Models
 */
class Project extends BaseModel
{

    use SerializedLobTrait;

    const SERIALIZED_LOB_COLUMN = 'attributes';

    /**
     * @var string
     */
    protected $table = 'projects';

    /**
     * @var array
     */
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
        'bitbucket_webhook_secret',
        'bitbucket_webhook_user_id',
    ];

    /**
     * @param $value
     */
    public function setStageAttribute($value)
    {
        $this->attributes['stage'] = $this->nullIfBlank($value);
    }

    /**
     * @param $value
     */
    public function setEmailNotificationRecipientAttribute($value)
    {
        $this->attributes['email_notification_recipient'] = $this->nullIfBlank($value);
    }

    /**
     * @param $value
     */
    public function setDaysToKeepDeploymentsAttribute($value)
    {
        $this->attributes['days_to_keep_deployments'] = $this->nullIfBlank($value);
    }

    /**
     * @param $value
     */
    public function setMaxNumberOfDeploymentsToKeepAttribute($value)
    {
        $this->attributes['max_number_of_deployments_to_keep'] = $this->nullIfBlank($value);
    }

    /**
     * @param $value
     */
    public function setGithubWebhookSecretAttribute($value)
    {
        $this->attributes['github_webhook_secret'] = $this->nullIfBlank($value);
    }

    /**
     * @param $value
     */
    public function setGithubWebhookUserIdAttribute($value)
    {
        $this->attributes['github_webhook_user_id'] = $this->nullIfBlank($value);
    }

    /**
     * @param $value
     */
    public function setBitbucketWebhookSecretAttribute($value)
    {
        $this->attributes['bitbucket_webhook_secret'] = $this->nullIfBlank($value);
    }

    /**
     * @param $value
     */
    public function setBitbucketWebhookUserIdAttribute($value)
    {
        $this->attributes['bitbucket_webhook_user_id'] = $this->nullIfBlank($value);
    }

    /**
     * @return HasOne
     */
    public function maxDeployment()
    {
        return $this->hasOne('App\Models\MaxDeployment');
    }

    /**
     * @return HasMany
     */
    public function deployments()
    {
        return $this->hasMany('App\Models\Deployment');
    }

    /**
     * @return BelongsToMany
     */
    public function recipes()
    {
        return $this->belongsToMany('App\Models\Recipe');
    }

    /**
     * @return BelongsTo
     */
    public function githubWebhookUser()
    {
        return $this->belongsTo('App\Models\User', 'github_webhook_user_id');
    }

    /**
     * @return Model|BelongsTo|object|null
     */
    public function getGithubWebhookUser()
    {
        return $this->githubWebhookUser()->first();
    }

    /**
     * @return BelongsTo
     */
    public function bitbucketWebhookUser()
    {
        return $this->belongsTo('App\Models\User', 'bitbucket_webhook_user_id');
    }

    /**
     * @return Model|BelongsTo|object|null
     */
    public function getBitbucketWebhookUser()
    {
        return $this->bitbucketWebhookUser()->first();
    }

    /**
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getMaxDeployment()
    {
        return $this->maxDeployment()->lockForUpdate()->first();
    }

    /**
     * @return Model|HasMany|object|null
     */
    public function lastFreeProjectDeployment()
    {
        return $this->deployments()->where('status', null)
            ->orderBy('number', 'asc')
            ->first();
    }

    /**
     * @return Model|HasMany|object|null
     */
    public function lastProjectDeployment()
    {
        $runningProject = $this->deployments()->where('status', 3)
            ->orderBy('number', 'asc')
            ->first();
        if ($runningProject === null) {
            return $this->lastFreeProjectDeployment();
        }
        return $runningProject;
    }

    /**
     * @return Model|HasMany|object|null
     */
    public function hasActiveProjectDeployment()
    {
        return $this->deployments()->where('status', 3)
            ->orderBy('number', 'asc')
            ->first();
    }

    /**
     * @return Model|HasMany|object|null
     */
    public function getLastDeployment()
    {
        return $this->deployments()->orderBy('number', 'desc')->first();
    }

    /**
     * @param $number
     * @return Model|HasMany|object|null
     */
    public function getDeploymentByNumber($number)
    {
        return $this->deployments()->where('number', $number)->first();
    }

    /**
     * @param int $page
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getDeploymentsByPage($page = 1, $limit = 10)
    {
        return $this->deployments()
            ->orderBy('deployments.created_at', 'desc')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDeployments()
    {
        return $this->deployments()->orderBy('number', 'desc')->get();
    }

    /**
     * @param Collection $deployments
     * @return mixed
     */
    public function deleteDeployments(Collection $deployments)
    {
        $deploymentIds = [];

        foreach ($deployments as $deployment) {
            $deploymentIds[] = $deployment->id;
        }

        return $this->deployments()
            ->whereIn('id', $deploymentIds)
            ->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecipes()
    {
        return $this->recipes()->orderBy('recipe_order')->get();
    }

    /**
     * @param array $data
     * @return Model
     */
    public function addMaxDeployment(array $data = [])
    {
        return $this->maxDeployment()->create($data);
    }

    /**
     * @param array $data
     * @return Model
     */
    public function addDeployment(array $data)
    {
        return $this->deployments()->create($data);
    }

    /**
     * @param array $data
     * @return bool|int
     */
    public function updateDeployment(array $data)
    {
        $deployment = $this->deployments()
            ->where('number', $data['number'])
            ->first();
        if ($deployment->status === 1 && $data['status'] === Deploy::RUNNING_DEPLOYMENT_STATUS) {
            return false;
        } else {
            return $this->deployments()
                ->where('number', $data['number'])
                ->update($data);
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function syncRecipes(array $data)
    {
        foreach ($data as $i => $recipeId) {
            $syncRecipeIds[$recipeId] = ['recipe_order' => $i + 1];
        }

        return $this->recipes()->sync($syncRecipeIds);
    }

    /**
     * @param array $data
     * @return int
     */
    public function updateMaxDeployment(array $data)
    {
        return $this->maxDeployment()->update($data);
    }

    /**
     * @param DateTime $date
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDeploymentsWhereCreatedAtBefore(DateTime $date)
    {
        return $this->deployments()
            ->orderBy('number', 'desc')
            ->where('created_at', '<', $date)
            ->get();
    }

    /**
     * @param $number
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDeploymentsWhereNumberBefore($number)
    {
        return $this->deployments()
            ->orderBy('number', 'desc')
            ->where('number', '<', $number)
            ->get();
    }

    /**
     * @param DeploymentSpecification $spec
     * @return mixed
     */
    public function getSatisfyingDeployments(DeploymentSpecification $spec)
    {
        return $spec->satisfyingElementsFrom($this);
    }

    /**
     * @inheritDoc
     */
    protected function getSerializationColumn(): string
    {
        return self::SERIALIZED_LOB_COLUMN;
    }

    /**
     * @inheritDoc
     */
    protected function getSerializationType(): string
    {
        return \Ngmy\EloquentSerializedLob\Serializers\JsonSerializer::class;
    }

    /**
     * @inheritDoc
     */
    protected function getDeserializationType(): string
    {
        return \App\Entities\ProjectAttribute\ProjectAttributeEntity::class;
    }
}
