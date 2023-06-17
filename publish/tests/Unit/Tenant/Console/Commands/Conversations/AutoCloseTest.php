<?php

namespace Tests\Chat\Unit\Tenant;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use ReesMcIvor\Chat\Models\Conversation;
use Tests\TenantTestCase;
use App\Models\User;
use ReesMcIvor\Chat\Events\NewChatMessage;

class AutoCloseTest extends TenantTestCase
{

    use RefreshDatabase;

    #[Test]
    public function a_auto_message_for_stale_conversation_is_created()
    {
        $user = User::factory()->create();
        $chat = Conversation::factory()->create([ 'subject' => 'Sports Injury' ]);
        $chat->participants()->attach($user->id);

        $chat->messages()->create([
            'user_id' => $user->id,
            'content' => 'I have a sports injury'
        ]);

        $chat->refresh();

        $this->travelTo(now()->addMinutes(Conversation::getAutoCloseAfterMinutes() + 1));

        if($chat->updated_at->diffInMinutes(now()) > Conversation::getAutoCloseAfterMinutes()) {
            $chat->messages()->create([
                'user_id' => 1,
                'content' => 'This conversation will be closed in 10 minutes due to inactivity.'
            ]);
        }

        $this->assertDatabaseHas('messages', [
            'user_id' => 1,
            'content' => 'This conversation will be closed in 10 minutes due to inactivity.'
        ]);
    }

}
