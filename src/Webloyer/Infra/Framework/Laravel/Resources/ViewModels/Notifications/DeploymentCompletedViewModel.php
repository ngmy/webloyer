<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Notifications;

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Spatie\ViewModels\ViewModel;
use Webloyer\Domain\Model\Project\Project;
use Webloyer\Domain\Model\Deployment\DeploymentCompleted;

class DeploymentCompletedViewModel extends ViewModel
{
    /** @var DeploymentCompleted */
    private $event;
    /** @var Project */
    private $project;
    /** @var AnsiToHtmlConverter */
    private $converter;

    /**
     * @param DeploymentCompleted $event
     * @param Project             $project
     * @param AnsiToHtmlConverter $converter
     * @return void
     */
    public function __construct(
        DeploymentCompleted $event,
        Project $project,
        AnsiToHtmlConverter $converter
    ) {
        $this->event = $event;
        $this->project = $project;
        $this->converter = $converter;
    }

    /**
     * @return DeploymentCompleted
     */
    public function event(): DeploymentCompleted
    {
        return $this->event;
    }

    /**
     * @return Project
     */
    public function project(): Project
    {
        return $this->project;
    }

    /**
     * @return string
     */
    public function subject(): string
    {
        return sprintf(
            'Deployment of %s #%s completed: %s',
            $this->project->name(),
            $this->event->number(),
            $this->event->status(),
        );
    }

    /**
     * @return string
     */
    public function deploymentUrl(): string
    {
        return route('projects.deployments.show', [
            'project' => $this->event->projectId(),
            'deployment' => $this->event->number(),
        ]);
    }

    /**
     * @return string
     */
    public function deploymentLog(): string
    {
        $logWithHtmlTags = $this->converter->convert($this->event->log());
        return htmlspecialchars_decode(strip_tags($logWithHtmlTags));
    }
}
