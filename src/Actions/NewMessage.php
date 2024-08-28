<?php

namespace ReesMcIvor\Chat\Actions;

use App\Models\Role;
use App\Models\User;
use ReesMcIvor\Chat\Models\Message;
use ReesMcIvor\Chat\Notifications\NewConversationNotification;
use ReesMcIvor\Chat\Notifications\NewCustomerMessageNotification;
use ReesMcIvor\Chat\Notifications\NewMessageNotification;

class NewMessage {

    public function handle( Message $message )
    {
        if($message->conversation->refresh()->messages()->count() == 1) {
            User::whereIn('email',['hello@logicrises.co.uk','oli@optimal-movement.co.uk'])
                ->get()->each(function ($admin) use ($message) {
                    $admin->notify(new NewConversationNotification($message));
                });
        }

        if($message?->user->is_premium) {
            $admins = $message->conversation->participants();
            $admins->where('role_id', [Role::STAFF_ROLE_ID])->each(function($staff) use ($message) {
                $staff->notify(new NewMessageNotification($message));
            });
            User::whereIn('email', ['oli@optimal-movement.co.uk'])->get()->each(function ($admin) use ($message) {
                $admin->notify(new NewMessageNotification($message));
            });
        }

        $message->conversation->participants->each(function($participant) use ($message) {
            if(
                $participant->is_premium &&
                $participant->id != $message->user_id &&
                $participant->role_id == Role::CUSTOMER_ROLE_ID &&
                !$message->is_system
            ) {
                $participant->notify(new NewCustomerMessageNotification($message));
            }
        });
    }

}
