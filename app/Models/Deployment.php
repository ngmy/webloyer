<?php

namespace App\Models;

use Robbo\Presenter\PresentableInterface;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;

class Deployment extends BaseModel implements PresentableInterface
{
    protected $fillable = [
        'project_id',
        'number',
        'task',
        'status',
        'message',
        'user_id',
    ];

    protected $casts = [
        'number' => 'integer',
        'status' => 'integer',
    ];

    /**
     * Return a created presenter.
     *
     * @return \Robbo\Presenter\Presenter
     */
    public function getPresenter()
    {
        $converter = new AnsiToHtmlConverter;
        return new DeploymentPresenter($this, $converter);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
