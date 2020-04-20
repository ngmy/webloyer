<?php

declare(strict_types=1);

namespace Webloyer\Infra\Db\Eloquents\Deployment;

use Illuminate\Database\Eloquent\{
    Builder,
    Model,
    Relations,
};
use InvalidArgumentException;
use Webloyer\Domain\Model\Deployment as DeploymentDomainModel;
use Webloyer\Infra\Db\Eloquents;

class Deployment extends Model implements DeploymentDomainModel\DeploymentInterest
{
    /** @var array<int, string> */
    protected $fillable = [
        'project_id',
        'number',
        'task',
        'status',
        'log',
        'user_id',
    ];

    /**
     * @param Builder $query
     * @param string  $projectId
     * @param int     $number
     * @return Builder
     */
    public function scopeOfId(Builder $query, string $projectId, int $number): Builder
    {
        return $query->where('project_id', $projectId)->where('number', $number);
    }

    /**
     * @return Relations\BelongsTo
     */
    public function project(): Relations\BelongsTo
    {
        return $this->belongsTo(Eloquents\Project\Project::class);
    }

    /**
     * @return Relations\BelongsTo
     */
    public function user(): Relations\BelongsTo
    {
        return $this->belongsTo(Eloquents\User\User::class);
    }

    /**
     * @param string $projectId
     * @return void
     */
    public function informProjectId(string $projectId): void
    {
        $projectOrm = Eloquents\Project\Project::ofId($projectId)->first();
        if (is_null($projectOrm)) {
            throw new InvalidArgumentException(
                'Project does not exists.' . PHP_EOL .
                'Project Id: ' . $projectId
            );
        }
        $this->project_id = $projectOrm->id;
    }

    /**
     * @param int $number
     * @return void
     */
    public function informNumber(int $number): void
    {
        $this->number = $number;
    }

    /**
     * @param string $task
     * @return void
     */
    public function informTask(string $task): void
    {
        $this->task = $task;
    }

    /**
     * @param string $status
     * @return void
     */
    public function informStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @param string $log
     * @return void
     */
    public function informLog(string $log): void
    {
        $this->log = $log;
    }

    /**
     * @param string $executor
     * @return void
     */
    public function informExecutor(string $executor): void
    {
        // TODO ユーザ削除されたら？
        $userOrm = Eloquents\User\User::ofEmail($executor)->first();
        if (is_null($userOrm)) {
            throw new InvalidArgumentException(
                'User does not exists.' . PHP_EOL .
                'User Email: ' . $executor
            );
        }
        $this->user_id = $userOrm->id;
    }

    /**
     * @return DeploymentDomainModel\Deployment
     */
    public function toEntity(): DeploymentDomainModel\Deployment
    {
        // TODO ユーザ削除されたら？
        assert(!is_null($this->project));
        return DeploymentDomainModel\Deployment::of(
            $this->project->uuid,
            $this->number,
            $this->task,
            $this->status,
            $this->log,
            $this->user->id
        );
    }
}
