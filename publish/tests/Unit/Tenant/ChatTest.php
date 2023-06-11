<?php

namespace Tests\Chat\Unit\Tenant;

use PHPUnit\Framework\Attributes\Test;
use ReesMcIvor\Chat\Models\Conversation;
use Tests\TenantTestCase;
use App\Models\User;

class ChatTest extends TenantTestCase {

    #[Test]
    public function a_conversation_can_be_created()
    {
        $user = User::factory()->create();
        $chat = Conversation::factory()->create([ 'subject' => 'Sports Injury' ]);
        $chat->participants()->attach($user->id);
        $this->assertEquals('Sports Injury', Conversation::first()->subject);
    }

    #[Test]
    public function a_conversation_can_have_a_message()
    {
        $user = User::factory()->create();
        $chat = Conversation::factory()->create([ 'subject' => 'Sports Injury' ]);
        $chat->participants()->attach($user->id);
        $chat->messages()->create([
            'user_id' => $user->id,
            'content' => 'I have a sports injury'
        ]);
        $this->assertEquals('I have a sports injury', Conversation::first()->messages->first()->content);
    }

    #[Test]
    public function a_conversation_can_have_multiple_messages()
    {
        $user = User::factory()->create();
        $chat = Conversation::factory()->create([ 'subject' => 'Sports Injury' ]);
        $chat->participants()->attach($user->id);
        $chat->messages()->create([
            'user_id' => $user->id,
            'content' => 'I have a sports injury'
        ]);
        $chat->messages()->create([
            'user_id' => $user->id,
            'content' => 'I have a sports injury'
        ]);
        $this->assertEquals(2, Conversation::first()->messages->count());
    }

    #[Test]
    public function a_conversation_can_have_a_participant()
    {
        $user = User::factory()->create();
        $chat = Conversation::factory()->create([ 'subject' => 'Sports Injury' ]);
        $chat->participants()->attach($user->id);
        $this->assertEquals($user->id, Conversation::first()->participants->first()->id);
    }

    #[Test]
    public function a_conversation_can_have_multiple_participants()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $chat = Conversation::factory()->create([ 'subject' => 'Sports Injury' ]);
        $chat->participants()->attach($user->id);
        $chat->participants()->attach($user2->id);
        $this->assertEquals(2, Conversation::first()->participants->count());
    }

    #[Test]
    public function a_conversation_can_be_closed()
    {
        $user = User::factory()->create();
        $chat = Conversation::factory()->create([ 'subject' => 'Sports Injury' ]);
        $chat->participants()->attach($user->id);
        $chat->close();
        $this->assertEquals('closed', Conversation::first()->status);
    }

}
