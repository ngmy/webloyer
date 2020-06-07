<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Deployment;

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Spatie\ViewModels\ViewModel;

class ShowViewModel extends ViewModel
{
    private $deployment;
    private $projectId;
    private $converter;

    public function __construct(
        object $deployment,
        string $projectId,
        AnsiToHtmlConverter $converter
    ) {
        $this->deployment = $deployment;
        $this->projectId = $projectId;
        $this->converter = $converter;
    }

    public function deployment(): object
    {
        return $this->deployment;
    }

    public function projectId(): string
    {
        return $this->projectId;
    }

    public function deploymentLog(): string
    {
        return $this->converter->convert($this->deployment->log);
    }
}
