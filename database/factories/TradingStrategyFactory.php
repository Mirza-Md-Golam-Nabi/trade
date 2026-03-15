<?php
namespace Database\Factories;

use App\Enums\RiskLevel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TradingStrategy>
 */
class TradingStrategyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = [
            'Breakout',
            'Pullback / Retracement',
            'Smart Money Concepts',
            'Price Action',
            'Support & Resistance',
            'Trend Continuation',
            'Reversal / Counter-Trend',
            'Range-Bound / Mean Reversion',
        ];

        $descriptions = [
            'When price breaks above resistance or below support with strong momentum.',
            'Enter after a temporary price retracement in the direction of the main trend',
            'Trade using institutional logic like liquidity grabs, BOS, and order blocks',
            'Trade based on candlestick patterns, market structure, and key levels—no indicators.',
            'Enter trades near major horizontal levels where price has reacted before.',
            'Enter trades in the direction of a strong trend after confirmation, using moving averages or higher highs/lows.',
            'Trade against the current trend at key exhaustion points, often confirmed by divergence, double tops/bottoms, or strong rejection candles.',
            'Enter trades near the top or bottom of a sideways market, targeting the opposite side of the range or a return to the mean.',
        ];

        $index = $this->faker->numberBetween(0, count($names) - 1);

        return [
            'user_id' => User::factory(),
            'name' => $names[$index],
            'description' => $descriptions[$index],
            'risk_level' => $this->faker->randomElement(RiskLevel::cases()),
        ];
    }
}
