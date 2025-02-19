<?php

namespace ReesMcIvor\Chat\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Expo\ExpoChannel;
use NotificationChannels\Expo\ExpoMessage;
use ReesMcIvor\Chat\Models\Message;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via( $notifiable ) : array
    {
        $channels = ['database', 'slack' /*'mail'*/];
        $expoTokens = $notifiable->routeNotificationForExpo($this);
        if(count($expoTokens)) {
            $channels[] = ExpoChannel::class;
        }
        Log::debug('Channels: ' . json_encode($channels));
        return $channels;
    }

    public function toMail()
    {
        return (new MailMessage())
            ->subject($this->getSubject())
            ->line("A new message has been left for customer")
            ->action('View Customer', route('customers', $this->message->creator->id));
    }

    public function toSlack()
    {
        return (new SlackMessage)
            ->content(__CLASS__ . ': ' . date('r') . ' :: ' .  $this->getSubject());
    }

    public function toExpo()
    {
        return ExpoMessage::create()
            ->title("New Message")
            ->body("You have been left a new message.")
            ->badge(1);
    }

    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->message->creator->id,
            'message' => "A new message has been left for you.",
        ];
    }

    protected function getSubject() : string
    {
        return 'A new message has been left by ' . $this->message->creator->name;
    }

}
