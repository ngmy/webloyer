<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Notifications;

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Spatie\ViewModels\ViewModel;
use Webloyer\Domain\Model\Project\Project;
use Webloyer\Domain\Model\Deployment\DeploymentCompleted;

class DeploymentCompletedViewModel extends ViewModel
{
    private $event;
    private $project;
    private $converter;

    public function __construct(
        DeploymentCompleted $event,
        Project $project,
        AnsiToHtmlConverter $converter
    ) {
        $this->event = $event;
        $this->project = $project;
        $this->converter = $converter;
    }

    public function event(): DeploymentCompleted
    {
        return $this->event;
    }

    public function project(): Project
    {
        return $this->project;
    }

    public function subject(): string
    {
        return sprintf('Deployment of %s #%s completed: %s',
            $this->project->name(),
            $this->event->number(),
            $this->event->status(),
        );
    }

    public function deploymentUrl(): string
    {
        return route('projects.deployments.show', [
            'project' => $this->event->projectId(),
            'deployment' => $this->event->number(),
        ]);
    }

    public function deploymentLog(): string
    {
        $logWithHtmlTags = $this->converter->convert($this->event->log());
        return htmlspecialchars_decode(strip_tags($logWithHtmlTags));
    }
}
