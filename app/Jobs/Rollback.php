<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Repositories\Project\ProjectInterface;
use App\Repositories\Server\ServerInterface;
use App\Repositories\Setting\SettingInterface;
use App\Services\Notification\NotifierInterface;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;

use Symfony\Component\Process\ProcessBuilder;

class Rollback extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $deployment;

    protected $executable;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $deployment
     * @return void
     */
    public function __construct(Model $deployment)
    {
        $this->deployment = $deployment;
        $this->executable = base_path('vendor/bin/dep');
    }

    /**
     * Execute the job.
     *
     * @param \App\Repositories\Project\ProjectInterface   $projectRepository
     * @param \App\Repositories\Server\ServerInterface     $serverRepository
     * @param \Symfony\Component\Process\ProcessBuilder    $processBuilder
     * @param \App\Services\Notification\NotifierInterface $notifier
     * @param \App\Repositories\Setting\SettingInterface   $settingRepository
     * @return void
     */
    public function handle(ProjectInterface $projectRepository, ServerInterface $serverRepository, ProcessBuilder $processBuilder, NotifierInterface $notifier, SettingInterface $settingRepository)
    {
        $deployment = $this->deployment;
        $project    = $projectRepository->byId($deployment->project_id);
        $server     = $serverRepository->byId($project->server_id);

        $app = app();

        // Create a server list file
        $serverListFileBuilder = $app->make('App\Services\Deployment\DeployerServerListFileBuilder')
            ->setServer($server)
            ->setProject($project);
        $serverListFile = $app->make('App\Services\Deployment\DeployerFileDirector', [$serverListFileBuilder])->construct();

        // Create recipe files
        foreach ($project->getRecipes() as $i => $recipe) {
            // HACK: If an instance of DeployerRecipeFileBuilder class is not stored in an array, a destructor is called and a recipe file is deleted immediately.
            $recipeFileBuilders[] = $app->make('App\Services\Deployment\DeployerRecipeFileBuilder')->setRecipe($recipe);
            $recipeFiles[] = $app->make('App\Services\Deployment\DeployerFileDirector', [$recipeFileBuilders[$i]])->construct();
        }

        // Create a deployment file
        $deploymentFileBuilder = $app->make('App\Services\Deployment\DeployerDeploymentFileBuilder')
            ->setProject($project)
            ->setServerListFile($serverListFile)
            ->setRecipeFile($recipeFiles);
        $deploymentFile = $app->make('App\Services\Deployment\DeployerFileDirector', [$deploymentFileBuilder])->construct();

        // Create a command
        $processBuilder
            ->add($this->executable)
            ->add("-f={$deploymentFile->getFullPath()}")
            ->add('--ansi')
            ->add('-n')
            ->add('-vv')
            ->add('rollback')
            ->add($project->stage);

        // Run the command
        $tmp['id']      = $deployment->id;
        $tmp['message'] = '';

        $process = $processBuilder->getProcess();
        $process->setTimeout(600);
        $process->run(function ($type, $buffer) use (&$tmp, $project, $deployment) {
            $tmp['message'] .= $buffer;
            $tmp['number']   = $deployment->number;

            $project->updateDeployment($tmp);
        });

        // Store the result
        if ($process->isSuccessful()) {
            $message = $process->getOutput();
        } else {
            $message = $process->getErrorOutput();
        }

        $data['id']      = $deployment->id;
        $data['number']  = $deployment->number;
        $data['message'] = $message;
        $data['status']  = $process->getExitCode();

        $project->updateDeployment($data);

        // Notify
        if (isset($project->email_notification_recipient)) {
            $mailSettings = $settingRepository->byType('mail');

            if (isset($mailSettings->attributes->getFrom()['address'])) {
                $fromAddress = $mailSettings->attributes->getFrom()['address'];
            } else {
                $fromAddress = null;
            }

            if (isset($mailSettings->attributes->getFrom()['name'])) {
                $fromName = $mailSettings->attributes->getFrom()['name'];
            } else {
                $fromName = null;
            }

            config(['mail.driver'       => $mailSettings->attributes->getDriver()]);
            config(['mail.from.address' => $fromAddress]);
            config(['mail.from.name'    => $fromName]);
            config(['mail.host'         => $mailSettings->attributes->getSmtpHost()]);
            config(['mail.port'         => $mailSettings->attributes->getSmtpPort()]);
            config(['mail.encryption'   => $mailSettings->attributes->getSmtpEncryption()]);
            config(['mail.username'     => $mailSettings->attributes->getSmtpUsername()]);
            config(['mail.password'     => $mailSettings->attributes->getSmtpPassword()]);
            config(['mail.sendmail'     => $mailSettings->attributes->getSendmailPath()]);

            $deployment = $project->getDeploymentByNumber($deployment->number);

            if ($process->isSuccessful()) {
                $status = 'success';
            } else {
                $status = 'failure';
            }
            $subject = "Deployment of {$project->name} #{$deployment->number} finished: {$status}";

            $message = view('emails.notification')
                ->with('project', $project)
                ->with('deployment', $deployment)
                ->render();

            $notifier->to($project->email_notification_recipient)->notify($subject, $message);
        }
    }
}
