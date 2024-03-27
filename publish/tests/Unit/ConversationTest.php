<?php

namespace Tests\Chat\Unit\Tenant;

use App\Notifications\Premium\NewConversationNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use ReesMcIvor\Chat\Models\Conversation;
use Tests\TenantTestCase;
use App\Models\User;
use Tests\TestCase;

class ConversationTest extends TestCase {

    /** @test */
    public function a_conversation_can_be_joined_by_a_user_with_roles()
    {
        Event::fake();
        Notification::fake();

        $user = User::factory()->create();
        $conversation = Conversation::factory()->create([ 'subject' => 'Sports Injury' ]);
        $conversation->participants()->attach($user->id);

        $conversation->messages()->create([
            'user_id' => $user->id,
            'content' => 'I have a sports injury'
        ]);
        
        Notification::assertSentTo(
            $conversation->participants,
            NewConversationNotification::class
        );
    }

}
