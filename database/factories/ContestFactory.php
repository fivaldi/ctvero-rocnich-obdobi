<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Contest;

class ContestFactory extends Factory
{
    protected $model = Contest::class;

    public function definition()
    {
        return [
            'name' => 'Test contest ' . $this->faker->words(6, true),
            'contest_start' => Carbon::now(),
            'contest_end' => '2099-01-01 00:00:00',
            'submission_start' => Carbon::now(),
            'submission_end' => '2099-01-01 00:00:00',
            'options' => NULL,
        ];
    }
}
