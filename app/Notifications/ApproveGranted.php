<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Orchid\Platform\Notifications\DashboardChannel;
use Orchid\Platform\Notifications\DashboardMessage;

class ApproveGranted extends Notification
{
    use Queueable;

    protected $community;
    protected $type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($objectInstance)
    {
        $this->getDetails($objectInstance);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [DashboardChannel::class];
    }

    /**
     * Get the dashboard representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Orchid\Platform\Notifications\DashboardMessage
     */
    public function toDashboard($notifiable)
    {
        return (new DashboardMessage)
            ->title('Approval Granted')
            ->message(sprintf('%s of community %s has been approved', $this->type, $this->community->name))
            ->action(url(sprintf('/communities/manage/%s', $this->community->id)));
    }

    /**
     * Get the details of the object instance.
     *
     * @param  mixed  $objectInstance
     * @return void
     */
    protected function getDetails($objectInstance)
    {
        $class = get_class($objectInstance);

        switch ($class) {
            case 'App\Models\Program':
                $this->community = $objectInstance->community;
                $this->type = 'Program';
                break;
            case 'App\Models\ProgramProgress':
                $this->community = $objectInstance->program->community;
                $this->type = 'Program Progress Report';
                break;
            case 'App\Models\Project':
                $this->community = $objectInstance->community;
                $this->type = 'Project';
                break;
            case 'App\Models\ProjectProgress':
                $this->community = $objectInstance->project->community;
                $this->type = 'Project Progress Report';
                break;
        }
    }
}
