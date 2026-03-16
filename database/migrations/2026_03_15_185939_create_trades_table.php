<?php

use App\Enums\Direction;
use App\Enums\RiskLevel;
use App\Enums\TradeStatus;
use App\Enums\TradeType;
use App\Enums\YesNo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedTinyInteger('market_type')->index();
            $table->unsignedBigInteger('account_id')->index();
            $table->unsignedBigInteger('trading_asset_id')->index();
            $table->unsignedBigInteger('trading_strategy_id')->index();
            $table->string('direction', 10)->default(Direction::Short);
            $table->string('risk_level')->nullable()->default(RiskLevel::Low);
            $table->string('trade_type', 10)->default(TradeType::Buy);
            $table->decimal('entry_price', 15, 4)->default(0);
            $table->decimal('lot_size', 15, 4)->nullable();
            $table->decimal('leverage', 10, 2)->default(1.00);
            $table->decimal('stop_loss', 15, 4)->nullable();
            $table->decimal('take_profit', 15, 4)->nullable();
            $table->decimal('trade_fee', 15, 4)->nullable()->default(0);
            $table->decimal('profit_loss', 15, 4)->nullable();
            $table->decimal('risk_percent', 15, 4)->nullable();
            $table->decimal('exit_price', 15, 4)->nullable();
            $table->string('notes')->nullable();
            $table->string('status')->default(TradeStatus::Open);
            $table->unsignedTinyInteger('open_close')->index()->default(YesNo::Yes);
            $table->dateTime('opened_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
