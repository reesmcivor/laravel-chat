<?php

namespace ReesMcIvor\Chat\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use ReesMcIvor\Chat\Models\Message;

class NewConversationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via()
    {
        return ['database', 'slack', 'mail'];
    }

    public function toMail()
    {
        return (new MailMessage())
            ->subject($this->getSubject())
            ->line("A new conversation has been started with customer")
            ->action('View Customer', route('customers', $this->message->creator->id));
    }

    public function toSlack()
    {
        return (new SlackMessage)
            ->content($this->getSubject());
    }

    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->message->creator->id,
            'message' => "A new conversation has been started with a therapist.",
        ];
    }

    protected function getSubject() : string
    {
        return 'A new conversation has been started with ' . $this->message->creator->name;
    }

}
