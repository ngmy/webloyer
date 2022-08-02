<?php

namespace App\Jobs;

use App\Services\Deployment\DeployerDeploymentFileBuilder;
use App\Services\Deployment\DeployerServerListFileBuilder;
use Illuminate\Bus\Queueable;
use Symfony\Component\Yaml\Parser;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class Job
 * @package App\Jobs
 */
abstract class Job
{

    const DEP_BASE_PATH = 'vendor/bin/dep';

    /*
    |--------------------------------------------------------------------------
    | Queueable Jobs
    |--------------------------------------------------------------------------
    |
    | This job base class provides a central location to place any logic that
    | is shared across all of your jobs. The trait included with the class
    | provides access to the "queueOn" and "delay" queue helper methods.
    |
    */

    use Queueable;

    /**
     * @var Parser
     */
    protected Parser $yamlParser;

    /**
     * @param $settingRepository
     * @param $project
     * @param $deployment
     * @param $process
     * @param $notifier
     */
    protected function notify($settingRepository, $project, $deployment, $process, $notifier) {

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

            config(['mail.driver' => $mailSettings->attributes->getDriver()]);
            config(['mail.from.address' => $fromAddress]);
            config(['mail.from.name' => $fromName]);
            config(['mail.host' => $mailSettings->attributes->getSmtpHost()]);
            config(['mail.port' => $mailSettings->attributes->getSmtpPort()]);
            config(['mail.encryption' => $mailSettings->attributes->getSmtpEncryption()]);
            config(['mail.username' => $mailSettings->attributes->getSmtpUsername()]);
            config(['mail.password' => $mailSettings->attributes->getSmtpPassword()]);
            config(['mail.sendmail' => $mailSettings->attributes->getSendmailPath()]);

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

    /**
     * @param $project
     * @param $server
     * @return array
     */
    protected function setEnviromentVariables($project, $server) {
        $this->yamlParser = new Parser();
        $hosts = $this->yamlParser->parse($server->body);

        $envVariables = [];
        if (isset($hosts[$project->stage]['password'])) {
            $postfix = '';
            if (getenv('PATH')) {
                $postfix .= ':' . getenv('PATH');
            }
            putenv('SSH_PATH=' . base_path() . '/deploy' . $postfix);
            putenv('SSH_PASSWORD=' . $hosts[$project->stage]['password']);
            $envVariables = [
                'SSH_PASSWORD' => getenv('SSH_PASSWORD'),
                'PATH' => getenv('SSH_PATH'),
            ];
        }
        return $envVariables;
    }
}
