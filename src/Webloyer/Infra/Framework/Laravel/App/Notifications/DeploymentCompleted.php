<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Webloyer\Domain\Model\Deployment\DeploymentCompleted as DeploymentCompletedEvent;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Notifications\DeploymentCompletedViewModel;

class DeploymentCompleted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        DeploymentCompletedEvent $event,
        object $project,
        object $user
    ) {
        $this->event = $event;
        $this->project = $project;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
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
            ->action('Show Deployment', $viewModel->deploymentUrl())
            ->line('Task: ' . $viewModel->event()->task())
            ->line('Log: ' . $viewModel->deploymentLog())
            ->line('Status: ' . $viewModel->event()->status())
            ;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
