<?php

namespace Tests\Chat\Feature\Tenant\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use ReesMcIvor\Chat\Models\Conversation;
use Tests\TenantTestCase;
use App\Models\User;

class ConversationsControllerTest extends TenantTestCase {

    protected $tenancy = true;

    #[Test]
    public function a_conversation_can_be_deleted()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $conversation = Conversation::factory()->create(['subject' => 'Sports Injury']);
        $this->deleteJson(route('tenant.conversations.destroy', ['conversation' => $conversation->id]))->assertSuccessful();
    }

    #[Test]
    public function a_conversation_can_be_joined()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $conversation = Conversation::factory()->create(['subject' => 'Sports Injury']);
        $this->getJson(route('tenant.conversations.join', ['conversation' => $conversation->id]))->assertSuccessful();
    }

}
