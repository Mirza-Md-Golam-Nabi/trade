<?php

use App\Enums\CommonStatus;
use App\Enums\MarketType;
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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('name', 100);
            $table->unsignedTinyInteger('market_type')->default(MarketType::Stock)->index();
            $table->decimal('initial_balance', 15, 2)->default(0)->comment('Initial balance in BDT');
            $table->string('notes', 255)->nullable();
            $table->unsignedTinyInteger('status')->default(CommonStatus::Active);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
