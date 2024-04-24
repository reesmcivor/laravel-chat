<?php

namespace ReesMcIvor\Chat\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use ReesMcIvor\Chat\Models\Message;

class NewMessageNotification extends Notification implements ShouldQueue
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
            ->line($this->getSubject())
            ->line($this->message->content)
            ->action('View Conversation', route('tenant.conversations.view', $this->message->conversation->id));
    }

    public function toSlack()
    {
        return (new SlackMessage)
            ->content($this->getSubject());
    }

    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'content' => $this->message,
        ];
    }

    protected function getSubject() : string
    {
        return 'A new message has been received from ' . $this->message->user->email;
    }

}
