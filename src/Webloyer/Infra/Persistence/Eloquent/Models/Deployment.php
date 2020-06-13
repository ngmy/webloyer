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

/**
 * Webloyer\Infra\Persistence\Eloquent\Models\Deployment
 *
 * @property int $id
 * @property int $project_id
 * @property int $number
 * @property string $task
 * @property string $status
 * @property string $log
 * @property int $user_id
 * @property string $request_date
 * @property string|null $start_date
 * @property string|null $finish_date
 * @property \CarbonImmutable $created_at
 * @property \CarbonImmutable $updated_at
 * @property-read \Webloyer\Infra\Persistence\Eloquent\Models\Project $project
 * @property-read \Webloyer\Infra\Persistence\Eloquent\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment ofId($projectId, $number)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment ofProjectId($projectId)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment whereFinishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment whereLog($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment whereRequestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment whereTask($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Webloyer\Infra\Persistence\Eloquent\Models\Deployment whereUserId($value)
 * @mixin \Eloquent
 */
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
        if (is_null($projectOrm)) {
            throw new InvalidArgumentException(
                'Project does not exists.' . PHP_EOL .
                'Project Id: ' . $projectId
            );
        }
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

    /**
     * @param string $requestDate
     * @return void
     */
    public function informRequestDate(string $requestDate): void
    {
        $this->request_date = $requestDate;
    }

    /**
     * @param string|null $startDate
     * @return void
     */
    public function informStartDate(?string $startDate): void
    {
        $this->start_date = $startDate;
    }

    /**
     * @param string|null $finishDate
     * @return void
     */
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
