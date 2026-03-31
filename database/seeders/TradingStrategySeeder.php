<?php
namespace Database\Seeders;

use App\Models\TradingStrategy;
use Illuminate\Database\Seeder;

class TradingStrategySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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

        foreach ($names as $key => $name) {
            TradingStrategy::create([
                'user_id'     => null,
                'name'        => $name,
                'description' => $descriptions[$key],
            ]);
        }
    }
}
