<?php

namespace Database\Seeders;

use App\Enums\MarketType;
use App\Models\TradingAsset;
use Illuminate\Database\Seeder;

class TradingAssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $symbols = [
            MarketType::Stock->value => ['AAPL', 'GOOGL', 'MSFT', 'AMZN', 'TSLA', 'META', 'NVDA'],
            MarketType::Forex->value => ['EUR/USD', 'GBP/USD', 'USD/JPY', 'AUD/USD', 'USD/CAD'],
            MarketType::Crypto->value => ['BTC', 'ETH', 'BNB', 'SOL', 'XRP', 'ADA', 'DOGE'],
        ];

        $assetNames = [
            MarketType::Stock->value => ['Apple', 'Alphabet', 'Microsoft', 'Amazon', 'Tesla', 'Meta', 'Nvidia'],
            MarketType::Forex->value => ['Euro/Dollar', 'Pound/Dollar', 'Dollar/Yen', 'Aussie/Dollar', 'Dollar/CAD'],
            MarketType::Crypto->value => ['Bitcoin', 'Ethereum', 'BNB', 'Solana', 'XRP', 'Cardano', 'Dogecoin'],
        ];

        foreach (MarketType::cases() as $type) {
            $loop = count($symbols[$type->value]);
            for ($i = 0; $i < $loop; $i++) {
                TradingAsset::create([
                    'user_id' => null,
                    'market_type' => $type,
                    'symbol' => $symbols[$type->value][$i],
                    'asset_name' => $assetNames[$type->value][$i],
                    'asset_icon' => null,
                ]);
            }
        }
    }
}
