<?php

namespace Tests\Chat\Unit\Tenant;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use ReesMcIvor\Chat\Models\Conversation;
use ReesMcIvor\Chat\Models\Message;
use Tests\TenantTestCase;
use App\Models\User;
use ReesMcIvor\Chat\Events\NewChatMessage;

class ChatMessageTest extends TenantTestCase
{

    use RefreshDatabase;

    #[Test]
    public function a_conversation_message_broadcasts_event()
    {
        Event::fake([
            NewChatMessage::class,
        ]);

        $user = User::factory()->create();
        $chat = Conversation::factory()->create([ 'subject' => 'Sports Injury' ]);
        $chat->participants()->attach($user->id);
        $chat->messages()->create([
            'user_id' => $user->id,
            'content' => 'I have a sports injury'
        ]);

        Event::assertDispatchedTimes(NewChatMessage::class, 1);

    }

}
