<?php

namespace ReesMcIvor\Chat\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Expo\ExpoChannel;
use NotificationChannels\Expo\ExpoMessage;
use ReesMcIvor\Chat\Models\Message;

class NewCustomerMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via( $notifiable ) : array
    {
        $channels = ['database', 'slack', 'mail'];
        $expoTokens = $notifiable->routeNotificationForExpo($this);
        if(count($expoTokens)) {
            $channels[] = ExpoChannel::class;
        }
        return $channels;
    }

    public function toMail()
    {
        return (new MailMessage())
            ->subject($this->getSubject())
            ->line("A new message has been sent to you on the Optimal Movement App.");
    }

    public function toSlack()
    {
        return (new SlackMessage)
            ->content($this->getSubject());
    }

    public function toExpo()
    {
        return ExpoMessage::create()
            ->title("You have a New Message")
            ->body("A new message has been sent to you on the Optimal Movement App.")
            ->badge(1);
    }

    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'message' => "A new message has been left for you by Optimal Movement.",
        ];
    }

    protected function getSubject() : string
    {
        return 'A new message has been left for you by Optimal Movement';
    }

}
