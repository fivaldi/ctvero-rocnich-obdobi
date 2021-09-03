<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contest;

class ContestFactory extends Factory
{
    protected $model = Contest::class;

    public function definition()
    {
        return [
            'name' => 'Test contest ' . $this->faker->words(6, true),
            'contest_start' => '1970-01-01 00:00:00',
            'contest_end' => '2099-01-01 00:00:00',
            'submission_start' => '1970-01-01 00:00:00',
            'submission_end' => '2099-01-01 00:00:00',
            'options' => NULL,
        ];
    }
}
