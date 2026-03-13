<?php
namespace Database\Factories;

use App\Enums\MarketType;
use App\Models\TradingAsset;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TradingAssetFactory extends Factory
{
    protected $model = TradingAsset::class;

    public function definition(): array
    {
        $marketType = $this->faker->randomElement(MarketType::cases());

        $symbols = [
            MarketType::Stock->value  => ['AAPL', 'GOOGL', 'MSFT', 'AMZN', 'TSLA', 'META', 'NVDA'],
            MarketType::Forex->value  => ['EUR/USD', 'GBP/USD', 'USD/JPY', 'AUD/USD', 'USD/CAD'],
            MarketType::Crypto->value => ['BTC', 'ETH', 'BNB', 'SOL', 'XRP', 'ADA', 'DOGE'],
        ];

        $assetNames = [
            MarketType::Stock->value  => ['Apple', 'Alphabet', 'Microsoft', 'Amazon', 'Tesla', 'Meta', 'Nvidia'],
            MarketType::Forex->value  => ['Euro/Dollar', 'Pound/Dollar', 'Dollar/Yen', 'Aussie/Dollar', 'Dollar/CAD'],
            MarketType::Crypto->value => ['Bitcoin', 'Ethereum', 'BNB', 'Solana', 'XRP', 'Cardano', 'Dogecoin'],
        ];

        $index = $this->faker->numberBetween(0, count($symbols[$marketType->value]) - 1);

        return [
            'user_id'     => User::factory(),
            'market_type' => $marketType,
            'symbol'      => $symbols[$marketType->value][$index],
            'asset_name'  => $assetNames[$marketType->value][$index],
        ];
    }
}
