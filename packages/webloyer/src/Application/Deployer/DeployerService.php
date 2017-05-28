<?php

namespace Ngmy\Webloyer\Webloyer\Application\Deployer;

use Ngmy\Webloyer\IdentityAccess\Application\User\UserService;
use Ngmy\Webloyer\Common\Notification\NotifierInterface;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentPresenter;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService;
use Ngmy\Webloyer\Webloyer\Application\Recipe\RecipeService;
use Ngmy\Webloyer\Webloyer\Application\Server\ServerService;
use Ngmy\Webloyer\Webloyer\Application\Setting\SettingService;
use Ngmy\Webloyer\Webloyer\Domain\Service\Deployer\DeployerDispatcherServiceInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerDeploymentFileBuilder;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFileDirector;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerRecipeFileBuilder;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerServerListFileBuilder;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Symfony\Component\Process\ProcessBuilder;

class DeployerService
{
    private $deployerDispatcherService;

    private $deploymentService;

    private $projectService;

    private $serverService;

    private $recipeService;

    private $settingService;

    private $processBuilder;

    private $notifier;

    private $userService;

    private $deployerExecutable;

    /**
     * __construct
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Service\Deployer\DeployerDispatcherServiceInterface $deployerDispatcherService
     * @param \Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService                   $deploymentService
     * @param \Ngmy\Webloyer\Webloyer\Application\Project\ProjectService                         $projectService
     * @param \Ngmy\Webloyer\Webloyer\Application\Server\ServerService                           $serverService
     * @param \Ngmy\Webloyer\Webloyer\Application\Recipe\RecipeService                           $recipeService
     * @param \Ngmy\Webloyer\Webloyer\Application\Setting\SettingService                         $settingService
     * @param \Symfony\Component\Process\ProcessBuilder                                          $processBuilder
     * @param \Ngmy\Webloyer\Common\Notification\NotifierInterface                               $notifier
     * @param \Ngmy\Webloyer\IdentityAccess\Application\User\UserService                         $userService
     * @return void
     */
    public function __construct(DeployerDispatcherServiceInterface $deployerDispatcherService, DeploymentService $deploymentService, ProjectService $projectService, ServerService $serverService, RecipeService $recipeService, SettingService $settingService, ProcessBuilder $processBuilder, NotifierInterface $notifier, UserService $userService)
    {
        $this->deployerDispatcherService = $deployerDispatcherService;
        $this->deploymentService = $deploymentService;
        $this->projectService = $projectService;
        $this->serverService = $serverService;
        $this->recipeService = $recipeService;
        $this->settingService = $settingService;
        $this->processBuilder = $processBuilder;
        $this->notifier = $notifier;
        $this->userService = $userService;
        $this->deployerExecutable = base_path('vendor/bin/dep');
    }

    public function dispatchDeployer($projectId, $deploymentId)
    {
        return $this->deployerDispatcherService->dispatch(
            $this->deploymentService->getDeploymentOfId($projectId, $deploymentId)
        );
    }

    public function runDeployer($projectId, $deploymentId)
    {
        $deployment = $this->deploymentService->getDeploymentOfId($projectId, $deploymentId);
        $project    = $this->projectService->getProjectOfId($projectId);
        $server     = $this->serverService->getServerOfId($project->serverId()->id());

        $app = app();

        // Create a server list file
        $serverListFileBuilder = $app->make(DeployerServerListFileBuilder::class)
            ->setServer($server)
            ->setProject($project);
        $serverListFile = $app->make(DeployerFileDirector::class, [$serverListFileBuilder])->construct();

        // Create recipe files
        foreach ($project->recipeIds() as $i => $recipeId) {
            $recipe = $this->recipeService->getRecipeOfId($recipeId->id());
            // HACK: If an instance of DeployerRecipeFileBuilder class is not stored in an array, a destructor is called and a recipe file is deleted immediately.
            $recipeFileBuilders[] = $app->make(DeployerRecipeFileBuilder::class)->setRecipe($recipe);
            $recipeFiles[] = $app->make(DeployerFileDirector::class, [$recipeFileBuilders[$i]])->construct();
        }

        // Create a deployment file
        $deploymentFileBuilder = $app->make(DeployerDeploymentFileBuilder::class)
            ->setProject($project)
            ->setServerListFile($serverListFile)
            ->setRecipeFile($recipeFiles);
        $deploymentFile = $app->make(DeployerFileDirector::class, [$deploymentFileBuilder])->construct();

        // Create a command
        $this->processBuilder
            ->add($this->deployerExecutable)
            ->add("-f={$deploymentFile->getFullPath()}")
            ->add('--ansi')
            ->add('-n')
            ->add('-vv')
            ->add($deployment->task()->value())
            ->add($project->stage());

        // Run the command
        $tmpMessage = '';

        $process = $this->processBuilder->getProcess();
        $process->setTimeout(600);
        $process->run(function ($type, $buffer) use (&$tmpMessage, $deployment, $process) {
            $tmpMessage .= $buffer;

            $this->deploymentService->saveDeployment(
                $deployment->projectId()->id(),
                $deployment->deploymentId()->id(),
                $deployment->task()->value(),
                $process->getExitCode(),
                $tmpMessage,
                $deployment->deployedUserId()->id()
            );
        });

        // Store the result
        if ($process->isSuccessful()) {
            $message = $process->getOutput();
        } else {
            $message = $process->getErrorOutput();
        }

        $deployment = $this->deploymentService->saveDeployment(
            $deployment->projectId()->id(),
            $deployment->deploymentId()->id(),
            $deployment->task()->value(),
            $process->getExitCode(),
            $message,
            $deployment->deployedUserId()->id()
        );

        // Notify
        if (!empty($project->emailNotificationRecipient())) {
            $mailSetting = $this->settingService->getMailSetting();
            $deployment = $this->deploymentService->getDeploymentOfId($deployment->projectId()->id(), $deployment->deploymentId()->id());

            if (isset($mailSetting->from()['address'])) {
                $fromAddress = $mailSetting->from()['address'];
            } else {
                $fromAddress = null;
            }

            if (isset($mailSetting->from()['name'])) {
                $fromName = $mailSetting->from()['name'];
            } else {
                $fromName = null;
            }

            config(['mail.driver'       => $mailSetting->driver()->value()]);
            config(['mail.from.address' => $fromAddress]);
            config(['mail.from.name'    => $fromName]);
            config(['mail.host'         => $mailSetting->smtpHost()]);
            config(['mail.port'         => $mailSetting->smtpPort()]);
            config(['mail.encryption'   => $mailSetting->smtpEncryption()->value()]);
            config(['mail.username'     => $mailSetting->smtpUserName()]);
            config(['mail.password'     => $mailSetting->smtpPassword()]);
            config(['mail.sendmail'     => $mailSetting->sendmailPath()]);

            if ($process->isSuccessful()) {
                $status = 'success';
            } else {
                $status = 'failure';
            }
            $subject = "Deployment of {$project->name()} #{$deployment->deploymentId()->id()} finished: {$status}";

            if (!is_null($deployment->deployedUserId()->id())) {
                $deployedUser = $this->userService->getUserOfId($deployment->deployedUserId()->id());
            }

            $deployment = new DeploymentPresenter($deployment, new AnsiToHtmlConverter());

            $message = view('emails.notification')
                ->with('project', $project)
                ->with('deployment', $deployment)
                ->with('deployedUser', $deployedUser)
                ->render();

            $this->notifier->to($project->emailNotificationRecipient())->notify($subject, $message);
        }
    }
}
