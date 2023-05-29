<?php

namespace ReesMcIvor\Chat\Database\Factories;

use Illuminate\Database\Seeder;
use ReesMcIvor\Chat\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition()
    {
        return [
            'subject' => $this->faker->sentence,
        ];
    }
}
