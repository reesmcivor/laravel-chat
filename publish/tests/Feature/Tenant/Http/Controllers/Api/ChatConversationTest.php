<?php

namespace Tests\Chat\Feature\Tenant\Http\Controllers\Api;

use PHPUnit\Framework\Attributes\Test;
use ReesMcIvor\Chat\Models\Conversation;
use Tests\TenantTestCase;
use App\Models\User;

class ChatConversationTest extends TenantTestCase {

    #[Test]
    public function conversations_can_be_listed()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $conversation = Conversation::factory()->create([
            'subject' => 'Sports Injury'
        ]);
        $conversation->participants()->attach([$user->id, $user2->id]);
        $conversation->messages()->create([ 'user_id' => $user->id, 'content' => 'Hello' ]);
        $conversation->messages()->create([ 'user_id' => $user2->id, 'content' => 'Hi' ]);

        $this->getJson('/api/chat/conversations')
            ->assertSuccessful()
            ->assertJsonStructure(['data' => [
                '*' => [
                    'subject',
                    'participants',
                    'messages',
                    'status'
                ]
            ]]);
    }

    #[Test]
    public function a_conversation_can_be_created()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user);
        $this->postJson('/api/chat/conversations', [
            'subject' => 'Sports Injury',
            'participants' => [$user2->id]
        ])->assertSuccessful();
        $this->assertDatabaseHas('chat_conversations', [
            'subject' => 'Sports Injury'
        ]);
    }


}
