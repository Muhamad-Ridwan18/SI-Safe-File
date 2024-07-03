<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\AccessRequest;

class AccessRequestApproved extends Notification
{
    use Queueable;

    protected $accessRequest;

    /**
     * Create a new notification instance.
     *
     * @param AccessRequest $accessRequest
     */
    public function __construct(AccessRequest $accessRequest)
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
        $approverName = $this->accessRequest->approver->name;

        return (new MailMessage)
            ->line("Your access request for the document '{$documentTitle}' has been approved by {$approverName}.")
            ->action('View Document', route('documents.index'))
            ->line('You can now access the document.');
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
            'document_title' => $this->accessRequest->document->title,
            'approver_name' => $this->accessRequest->approver->name,
            'message' => "Your access request for the document '{$this->accessRequest->document->title}' has been approved by {$this->accessRequest->approver->name}.",
            'link' => route('documents.index'),
        ];
    }
}
