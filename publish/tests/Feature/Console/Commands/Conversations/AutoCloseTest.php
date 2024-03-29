<?php

namespace Tests\Chat\Feture\Console\Commands\Conversations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use ReesMcIvor\Chat\Models\Conversation;
use Tests\TenantTestCase;
use App\Models\User;
use ReesMcIvor\Chat\Events\NewChatMessage;
use Tests\TestCase;

class AutoCloseTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_auto_message_for_stale_conversation_is_created()
    {

        Event::fake();

        $user = User::factory()->create();
        $chat = Conversation::factory()->create(['subject' => 'test']);

        $chat->participants()->attach($user->id);
        $chat->messages()->create([ 'user_id' => $user->id, 'content' => 'I have a sports injury' ]);
        $chat->refresh();

        $this->travelTo(now()->addMinutes($chat->getAutoCloseMins() + 10));
        Artisan::call("chat:conversations:auto_close");

        $this->assertDatabaseHas('messages', [
            'user_id' => 1,
            'content' => 'This conversation will be closed in 10 minutes due to inactivity.'
        ]);

        $this->travelTo(now()->addMinutes($chat->getAutoCloseLeniency() + 1));
        Artisan::call("chat:conversations:auto_close");

        $this->assertDatabaseHas('messages', [
            'user_id' => 1,
            'content' => 'This conversation has been closed.'
        ]);
    }

}
