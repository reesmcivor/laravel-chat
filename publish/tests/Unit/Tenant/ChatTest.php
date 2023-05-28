<?php

namespace Tests\Chat\Unit\Tenant;

use PHPUnit\Framework\Attributes\Test;
use Tests\TenantTestCase;

class ChatTest extends TenantTestCase {

    #[Test]
    public function a_user_can_contain_one_chat_thread()
    {
        $user = User::factory()->create();
        $chat = Chat::factory()->create()->user()->associate($user)->save();
    }

}
