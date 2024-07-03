<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccessRequestNotification extends Notification
{
    use Queueable;

    protected $accessRequest;

    /**
     * Create a new notification instance.
     *
     * @param $accessRequest
     */
    public function __construct($accessRequest)
    {
        $this->accessRequest = $accessRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; 
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $documentTitle = $this->accessRequest->document->title;
        $requesterName = $this->accessRequest->requester->name;

        return (new MailMessage)
            ->line("{$requesterName} has requested access to the document: {$documentTitle}")
            ->action('View Request', route('access-requests.index'))
            ->line('Please review and respond to the request.');
    }

    public function toDatabase($notifiable)
    {
        $documentTitle = $this->accessRequest->document->title;
        $requesterName = $this->accessRequest->requester->name;

        return [
            'document_title' => $documentTitle,
            'requester_name' => $requesterName,
            'message' => "{$requesterName} has requested access to the document: {$documentTitle}",
            'link' => route('access-requests.index'),
        ];
    }

}
