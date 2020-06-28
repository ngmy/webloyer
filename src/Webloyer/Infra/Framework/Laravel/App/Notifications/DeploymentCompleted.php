<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Webloyer\Domain\Model\Deployment\DeploymentCompleted as DeploymentCompletedEvent;
use Webloyer\Domain\Model\Project\Project;
use Webloyer\Domain\Model\User\User;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Notifications\DeploymentCompletedViewModel;

class DeploymentCompleted extends Notification
{
    use Queueable;

    /** @var DeploymentCompletedEvent */
    private $event;
    /** @var Project */
    private $project;
    /** @var User */
    private $user;

    /**
     * Create a new notification instance.
     *
     * @param DeploymentCompletedEvent $event
     * @param Project                  $project
     * @param User                     $user
     * @return void
     */
    public function __construct(
        DeploymentCompletedEvent $event,
        Project $project,
        User $user
    ) {
        $this->event = $event;
        $this->project = $project;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return list<string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // TODO injection?
        $viewModel = new DeploymentCompletedViewModel(
            $this->event,
            $this->project,
            new AnsiToHtmlConverter()
        );

        return (new MailMessage())
            ->subject($viewModel->subject())
            ->greeting('Hello!')
            ->line('Deployment completed!')
            ->action('Show Deployment', $viewModel->deploymentShowUrl())
            ->line('Task: ' . $viewModel->event()->task())
            ->line('Log: ' . $viewModel->deploymentLog())
            ->line('Status: ' . $viewModel->event()->status())
            ;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return list<string>
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
