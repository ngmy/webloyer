<?php
declare(strict_types=1);

namespace App\Models;

use Robbo\Presenter\PresentableInterface;
use Robbo\Presenter\Presenter;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;

/**
 * Class Deployment
 * @package App\Models
 */
class Deployment extends BaseModel implements PresentableInterface
{
    /**
     * @var string
     */
    protected $table = 'deployments';

    /**
     * @var array
     */
    protected $fillable = [
        'project_id',
        'number',
        'task',
        'status',
        'message',
        'user_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'number' => 'integer',
        'status' => 'integer',
    ];

    /**
     * Return a created presenter.
     *
     * @return DeploymentPresenter|Presenter
     */
    public function getPresenter()
    {
        $converter = new AnsiToHtmlConverter;
        return new DeploymentPresenter($this, $converter);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
