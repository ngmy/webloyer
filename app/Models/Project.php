<?php

namespace App\Models;

use App\Specifications\DeploymentSpecification;
use Ngmy\EloquentSerializedLob\SerializedLobTrait;
use Illuminate\Support\Collection;
use DateTime;

class Project extends BaseModel
{
    use SerializedLobTrait;

    protected $table = 'projects';

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

    public function setStageAttribute($value)
    {
        $this->attributes['stage'] = $this->nullIfBlank($value);
    }

    public function setEmailNotificationRecipientAttribute($value)
    {
        $this->attributes['email_notification_recipient'] = $this->nullIfBlank($value);
    }

    public function setDaysToKeepDeploymentsAttribute($value)
    {
        $this->attributes['days_to_keep_deployments'] = $this->nullIfBlank($value);
    }

    public function setMaxNumberOfDeploymentsToKeepAttribute($value)
    {
        $this->attributes['max_number_of_deployments_to_keep'] = $this->nullIfBlank($value);
    }

    public function setGithubWebhookSecretAttribute($value)
    {
        $this->attributes['github_webhook_secret'] = $this->nullIfBlank($value);
    }

    public function setGithubWebhookUserIdAttribute($value)
    {
        $this->attributes['github_webhook_user_id'] = $this->nullIfBlank($value);
    }

    public function maxDeployment()
    {
        return $this->hasOne('App\Models\MaxDeployment');
    }

    public function deployments()
    {
        return $this->hasMany('App\Models\Deployment');
    }

    public function recipes()
    {
        return $this->belongsToMany('App\Models\Recipe');
    }

    public function githubWebhookUser()
    {
        return $this->belongsTo('App\Models\User', 'github_webhook_user_id');
    }

    public function getGithubWebhookUser()
    {
        return $this->githubWebhookUser()->first();
    }

    public function getMaxDeployment()
    {
        return $this->maxDeployment()->lockForUpdate()->first();
    }

    public function getLastDeployment()
    {
        return $this->deployments()->orderBy('number', 'desc')->first();
    }

    public function getDeploymentByNumber($number)
    {
        return $this->deployments()->where('number', $number)->first();
    }

    public function getDeploymentsByPage($page = 1, $limit = 10)
    {
        return $this->deployments()
            ->orderBy('deployments.created_at', 'desc')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);
    }

    public function getDeployments()
    {
        return $this->deployments()->orderBy('number', 'desc')->get();
    }

    public function deleteDeployments(Collection $deployments)
    {
        foreach ($deployments as $deployment) {
            $deploymentIds[] = $deployment->id;
        }

        return $this->deployments()
            ->whereIn('id', $deploymentIds)
            ->delete();
    }

    public function getRecipes()
    {
        return $this->recipes()->orderBy('recipe_order')->get();
    }

    public function addMaxDeployment(array $data = [])
    {
        return $this->maxDeployment()->create($data);
    }

    public function addDeployment(array $data)
    {
        return $this->deployments()->create($data);
    }

    public function updateDeployment(array $data)
    {
        return $this->deployments()
            ->where('number', $data['number'])
            ->update($data);
    }

    public function syncRecipes(array $data)
    {
        foreach ($data as $i => $recipeId) {
            $syncRecipeIds[$recipeId] = ['recipe_order' => $i + 1];
        }

        return $this->recipes()->sync($syncRecipeIds);
    }

    public function updateMaxDeployment(array $data)
    {
        return $this->maxDeployment()->update($data);
    }

    public function getDeploymentsWhereCreatedAtBefore(DateTime $date)
    {
        return $this->deployments()
            ->orderBy('number', 'desc')
            ->where('created_at', '<', $date)
            ->get();
    }

    public function getDeploymentsWhereNumberBefore($number)
    {
        return $this->deployments()
            ->orderBy('number', 'desc')
            ->where('number', '<', $number)
            ->get();
    }

    public function getSatisfyingDeployments(DeploymentSpecification $spec)
    {
        return $spec->satisfyingElementsFrom($this);
    }

    protected function serializedLobColumn()
    {
        return 'attributes';
    }

    protected function serializedLobSerializer()
    {
        return \Ngmy\EloquentSerializedLob\Serializer\JsonSerializer::class;
    }

    protected function serializedLobDeserializeType()
    {
        return \App\Entities\ProjectAttribute\ProjectAttributeEntity::class;
    }
}
