<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Deployment;

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Spatie\ViewModels\ViewModel;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\ViewModelHelpers;

class ShowViewModel extends ViewModel
{
    use ViewModelHelpers;

    /** @var object */
    private $deployment;
    /** @var string */
    private $projectId;
    /** @var AnsiToHtmlConverter */
    private $converter;

    /**
     * @param object              $deployment
     * @param string              $projectId
     * @param AnsiToHtmlConverter $converter
     * @return void
     */
    public function __construct(
        object $deployment,
        string $projectId,
        AnsiToHtmlConverter $converter
    ) {
        $this->deployment = $deployment;
        $this->projectId = $projectId;
        $this->converter = $converter;
    }

    /**
     * @return object
     */
    public function deployment(): object
    {
        return $this->deployment;
    }

    /**
     * @return string
     */
    public function projectId(): string
    {
        return $this->projectId;
    }

    /**
     * @return string
     */
    public function deploymentUserEmail(): string
    {
        return $this->hyphenIfBlank($this->deployment->user->email);
    }

    /**
     * @return string
     */
    public function deploymentLog(): string
    {
        return $this->converter->convert($this->deployment->log);
    }
}
