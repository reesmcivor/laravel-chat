<?php

namespace Tests\Chat\Unit\Tenant;

use PHPUnit\Framework\Attributes\Test;
use ReesMcIvor\Chat\Models\Thread;
use Tests\TenantTestCase;
use App\Models\User;

class ChatTest extends TenantTestCase {

    #[Test]
    public function a_user_can_contain_one_chat_thread()
    {
        $user = User::factory()->create();
        $chat = Thread::factory()->create([
            'subject' => 'Sports Injury'
        ])->user()->associate($user)->save();

        $this->assertEquals('Sports Injury', Thread::first()->subject);
        $this->assertEquals($user->toArray(), Thread::first()->user->toArray());
    }

}
