<?php

namespace Tests\Chat\Feature\Tenant\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use ReesMcIvor\Chat\Models\Conversation;
use Tests\TenantTestCase;
use App\Models\User;

class ChatConversationTest extends TenantTestCase
{

    protected $tenancy = true;

    #[Test]
    public function conversations_can_be_listed()
    {

        Event::fake();

        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $conversation = Conversation::factory()->create(['subject' => 'Sports Injury', 'updated_at' => now()->addMinutes(10)]);
        $conversation->participants()->attach([$user->id, $user2->id]);
        $conversation->messages()->create(['user_id' => $user->id, 'content' => 'Hello there', 'updated_at' => now()->addMinutes(20)]);
        $conversation->messages()->create(['user_id' => $user2->id, 'content' => 'How can I help?', 'updated_at' => now()->addMinutes(30)]);

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
    public function conversations_are_listed_from_newest_to_oldest()
    {
        Event::fake();
        $conversation = Conversation::factory()->create(['updated_at' => now()->addMinutes(10)]);
        $conversation2 = Conversation::factory()->create(['updated_at' => now()->addMinutes(20)]);

        $this->getJson('/api/chat/conversations')
            ->assertSeeInOrder([$conversation2->subject, $conversation->subject]);
    }

    #[Test]
    public function a_authenticated_user_can_start_conversation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->postJson('/api/chat/conversations/create', [
            'subject' => 'Sports Injury'
        ])->assertSuccessful();

        $this->assertDatabaseHas(Conversation::class, [
            'subject' => 'Sports Injury'
        ]);
    }

    #[Test]
    public function a_conversation_can_contain_messages()
    {

        Event::fake();
        $user = User::factory()->create();
        $this->actingAs($user);

        $conversation = Conversation::factory()->create();
        $this->postJson(route('api.messages.create', $conversation->id), [
                'content' => 'This is a chat message'
            ])
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'content',
                    'user' => [
                        'id'
                    ]
                ]
            ]);
    }

}
