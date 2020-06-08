<?php

declare(strict_types=1);

namespace Webloyer\Infra\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;
use Webloyer\Domain\Model\Deployment\{
    Deployment as DeploymentEntity,
    DeploymentInterest,
};
use Webloyer\Infra\Persistence\Eloquent\ImmutableTimestampable;

class Deployment extends Model implements DeploymentInterest
{
    use ImmutableTimestampable;

    /** @var array<int, string> */
    protected $fillable = [
        'project_id',
        'number',
        'task',
        'status',
        'log',
        'user_id',
        'request_date',
        'start_date',
        'finish_date',
    ];

    /**
     * @param Builder $query
     * @param string  $projectId
     * @return Builder
     */
    public function scopeOfProjectId(Builder $query, string $projectId): Builder
    {
        $projectOrm = Project::ofId($projectId)->first();
        return $query->where('project_id', $projectOrm->id);
    }

    /**
     * @param Builder $query
     * @param string  $projectId
     * @param int     $number
     * @return Builder
     */
    public function scopeOfId(Builder $query, string $projectId, int $number): Builder
    {
        return $query->ofProjectId($projectId)->where('number', $number);
    }

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param string $projectId
     * @return void
     */
    public function informProjectId(string $projectId): void
    {
        $projectOrm = Project::ofId($projectId)->first();
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
        $userOrm = User::ofId($executor)->first();
        if (is_null($userOrm)) {
            throw new InvalidArgumentException(
                'User does not exists.' . PHP_EOL .
                'User Id: ' . $executor
            );
        }
        $this->user_id = $userOrm->id;
    }

    public function informRequestDate(string $requestDate): void
    {
        $this->request_date = $requestDate;
    }

    public function informStartDate(?string $startDate): void
    {
        $this->start_date = $startDate;
    }

    public function informFinishDate(?string $finishDate): void
    {
        $this->finish_date = $finishDate;
    }

    /**
     * @return DeploymentEntity
     */
    public function toEntity(): DeploymentEntity
    {
        // TODO ユーザ削除されたら？
        assert(!is_null($this->project));
        return DeploymentEntity::of(
            $this->project->uuid,
            (int) $this->number,
            $this->task,
            $this->status,
            $this->log,
            $this->user->uuid,
            $this->request_date,
            $this->start_date,
            $this->finish_date
        )
        ->setSurrogateId($this->id)
        ->setCreatedAt($this->created_at)
        ->setUpdatedAt($this->updated_at);
    }
}
