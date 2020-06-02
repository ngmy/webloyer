<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Webloyer\Domain\Model\Deployment\DeploymentCompleted as DeploymentCompletedEvent;

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
        $subject = sprintf('Deployment of %s #%s completed: %s',
            $this->project->name,
            $this->event->number(),
            $this->event->status(),
        );
        $url = route('projects.deployments.show', [
            'project' => $this->event->projectId(),
            'deployment' => $this->event->number(),
        ]);

        return (new MailMessage())
            ->subject($subject)
            ->greeting('Hello!')
            ->line('Deployment completed!')
            ->action('Show Deployment', $url)
            ->line('Task: ' . $this->event->task())
            ->line('Log: ' . $this->event->log())
            ->line('Status: ' . $this->event->status())
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
