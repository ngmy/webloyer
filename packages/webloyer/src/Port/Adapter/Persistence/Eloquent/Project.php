<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent;

use Ngmy\EloquentSerializedLob\SerializedLobTrait;
use Ngmy\EloquentSerializedLob\Serializer\JsonSerializer;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\KeepLastDeployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project as EntityProject;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectAttribute;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;
use Ngmy\Webloyer\Webloyer\Domain\Model\User\UserId;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\AbstractBaseEloquent;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Deployment;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\MaxDeployment;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Recipe;

class Project extends AbstractBaseEloquent
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
        return $this->hasOne(MaxDeployment::class);
    }

    public function deployments()
    {
        return $this->hasMany(Deployment::class);
    }

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class);
    }

    public function syncRecipes(array $data)
    {
        foreach ($data as $i => $recipeId) {
            $syncRecipeIds[$recipeId] = ['recipe_order' => $i + 1];
        }

        return $this->recipes()->sync($syncRecipeIds);
    }

    /**
     * Convert an Eloquent object into an entity.
     *
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project
     */
    public function toEntity()
    {
        return new EntityProject(
            new ProjectId($this->id),
            $this->name,
            $this->recipes()->orderBy('recipe_order')->get()->map(function ($recipe) {
                return new RecipeId($recipe->id);
            })->all(),
            new ServerId($this->server_id),
            $this->repository,
            $this->stage,
            $this->getAttribute('attributes'),
            $this->email_notification_recipient,
            $this->days_to_keep_deployments,
            $this->max_number_of_deployments_to_keep,
            new KeepLastDeployment($this->keep_last_deployment),
            $this->github_webhook_secret,
            new UserId($this->github_webhook_user_id),
            $this->created_at,
            $this->updated_at
        );
    }

    protected function serializedLobColumn()
    {
        return 'attributes';
    }

    protected function serializedLobSerializer()
    {
        return JsonSerializer::class;
    }

    protected function serializedLobDeserializeType()
    {
        return ProjectAttribute::class;
    }
}
