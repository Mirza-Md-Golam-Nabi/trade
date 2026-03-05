<?php
namespace App\Models;

use App\Enums\CommonStatus;
use App\Enums\MarketType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'name', 'market_type', 'initial_balance', 'notes', 'status'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected function casts(): array
    {
        return [
            'market_type' => MarketType::class,
            'status'      => CommonStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
