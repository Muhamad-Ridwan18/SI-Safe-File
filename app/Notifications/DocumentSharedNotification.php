<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentSharedNotification extends Notification
{
    use Queueable;

    protected $document;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($document)
    {
        $this->document = $document;
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
        $documentTitle = $this->document->title;

        return (new MailMessage)
            ->line('You have been granted access to the document: ' . $documentTitle)
            ->action('View Document', url('/documents/' . $this->document->id))
            ->line('Thank you for using our application!');
    }
}
