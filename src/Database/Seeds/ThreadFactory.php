<?php

namespace ReesMcIvor\Chat\Database\Seeds;

use Illuminate\Database\Seeder;
use ReesMcIvor\Chat\Models\Thread;

class ThreadFactory extends Seeder
{
    public function run(): void
    {
        Thread::factory()->create([
            'subject' => 'Help with my leg injury',
        ]);
    }
}
