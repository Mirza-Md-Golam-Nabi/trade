<?php

namespace Database\Factories;

use App\Enums\CommonStatus;
use App\Enums\MarketType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => rand(1, 2),
            'name' => fake()->word(2, true),
            'market_type' => fake()->randomElement(MarketType::cases()),
            'balance' => rand(1, 10) * 10000,
            'trade_fee' => rand(0, 3),
            'currency_id' => 1,
            'notes' => fake()->sentence(),
            'status' => fake()->randomElement(CommonStatus::cases()),
        ];
    }
}
