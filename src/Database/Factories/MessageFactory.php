<?php

namespace ReesMcIvor\Chat\Database\Factories;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use ReesMcIvor\Chat\Models\Message;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'user_id' => $this->faker->randomDigit,
            'content' => $this->faker->sentence
        ];
    }
}
