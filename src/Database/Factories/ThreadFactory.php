<?php

namespace ReesMcIvor\Chat\Database\Factories;

use Illuminate\Database\Seeder;
use ReesMcIvor\Chat\Models\Thread;
use Illuminate\Database\Eloquent\Factories\Factory;
use ReesMcIvor\Forms\Models\DateAnswer;

class ThreadFactory extends Factory
{
    protected $model = Thread::class;

    public function definition()
    {
        return [
            'user_id' => $this->faker->randomDigit,
            'subject' => $this->faker->sentence,
        ];
    }
}
