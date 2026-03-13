<?php
namespace App\Models;

use App\Enums\MarketType;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradingAsset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'market_type',
        'symbol',
        'asset_name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'market_type' => MarketType::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
