<?php
namespace App\Models;

use App\Enums\MarketType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradingAsset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'market_type',
        'symbol',
        'asset_name',
        'asset_icon',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'market_type' => MarketType::class,
        ];
    }
}
