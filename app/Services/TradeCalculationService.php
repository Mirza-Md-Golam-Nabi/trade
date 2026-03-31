<?php
namespace App\Services;

use App\Enums\TradeStatus;
use App\Enums\TradeType;
use App\Models\Account;
use App\Models\Trade;

class TradeCalculationService
{
    public function __construct(
        private readonly float $entry,
        private readonly float $stopLoss,
        private readonly float $lotSize,
        private readonly float $takeProfit,
        private readonly float $leverage,
        private readonly float $balance,
        private readonly float $feePct = 0,
    ) {}

    /**
     * Build from raw form data (used by the Filament form live-preview
     * and by TradeService::openPosition).
     */
    public static function fromForm(array $data): static
    {
        $accountId = $data['account_id'] ?? null;
        $balance   = 0;
        $fee       = 0;

        if ($accountId) {
            $account = Account::find($accountId);
            $balance = (float) ($account?->balance ?? 0);
            $fee     = (float) ($account?->trade_fee ?? 0);
        }

        return new static(
            entry: (float) ($data['entry_price'] ?? 0),
            stopLoss: (float) ($data['stop_loss'] ?? 0),
            lotSize: (float) ($data['lot_size'] ?? 0),
            takeProfit: (float) ($data['take_profit'] ?? 0),
            leverage: (float) ($data['leverage'] ?: 1),
            balance: $balance,
            feePct: $fee,
        );
    }

    /**
     * Build from a persisted Trade model (used by TradeService::closePosition).
     * Balance is loaded from the related account at close time.
     */
    public static function fromTrade(Trade $trade): static
    {
        $account = Account::findOrFail($trade->account_id);

        return new static(
            entry: (float) $trade->entry_price,
            stopLoss: (float) ($trade->stop_loss ?? 0),
            lotSize: (float) $trade->lot_size,
            takeProfit: (float) ($trade->take_profit ?? 0),
            leverage: (float) ($trade->leverage ?: 1),
            balance: (float) $account->balance,
            feePct: (float) $account->trade_fee,
        );
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Open-position metrics
    // ──────────────────────────────────────────────────────────────────────────

    // Margin: (lot_size × entry) / leverage
    public function margin(): ?float
    {
        if (! $this->entry || ! $this->lotSize) {
            return null;
        }

        return round(($this->lotSize * $this->entry) / $this->leverage, 4);
    }

    /**
     * Calculate the trade fee for either the open or close leg.
     *
     * Open Fee  = (Lot Size × Entry Price) × (Fee % / 100)
     * Close Fee = (Lot Size × Exit Price)  × (Fee % / 100)
     *
     * @param  TradeStatus  $status     Trade status: Open for entry fee, Closed for exit fee
     * @param  float|null   $exitPrice  Required when $status is Closed
     * @return float|null               Returns null if required values are missing
     */
    public function tradeFee(TradeStatus $status = TradeStatus::Open, ?float $exitPrice = null): ?float
    {
        return match ($status) {
            TradeStatus::Closed => $this->closeFee($exitPrice ?? 0),
            default             => $this->openFee(),
        };
    }

    // Open Fee
    public function openFee(): ?float
    {
        if (! $this->entry || ! $this->lotSize) {
            return null;
        }

        return round(($this->lotSize * $this->entry) * ($this->feePct / 100), 2);
    }

    // Close Fee
    public function closeFee(float $exitPrice): ?float
    {
        if (! $exitPrice || ! $this->lotSize) {
            return null;
        }

        return round(($this->lotSize * $exitPrice) * ($this->feePct / 100), 2);
    }

    // Risk Amount: |entry − stop_loss| × lot_size
    public function riskAmount(): ?float
    {
        if (! $this->entry || ! $this->stopLoss || ! $this->lotSize) {
            return null;
        }

        return round(abs($this->entry - $this->stopLoss) * $this->lotSize, 2);
    }

    // Risk %: (risk_amount / balance) × 100
    public function riskPercent(): ?float
    {
        $riskAmount = $this->riskAmount();

        if (! $riskAmount || ! $this->balance) {
            return null;
        }

        return round(($riskAmount / $this->balance) * 100, 2);
    }

    // RRR: |take_profit − entry| / |entry − stop_loss|
    public function rrr(): ?float
    {
        if (! $this->entry || ! $this->stopLoss || ! $this->takeProfit) {
            return null;
        }

        $risk = abs($this->entry - $this->stopLoss);

        if (! $risk) {
            return null;
        }

        return round(abs($this->takeProfit - $this->entry) / $risk, 2);
    }

    // Capital Usage %: margin / balance × 100
    public function capitalUsage(): ?float
    {
        $margin = $this->margin();

        if (! $margin || ! $this->balance) {
            return null;
        }

        return round(($margin / $this->balance) * 100, 2);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Close-position metrics
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * P&L on close.
     *
     * Buy / Long  → profit when exit > entry  (priceDiff positive)
     * Sell / Short → profit when exit < entry  (priceDiff reversed)
     *
     * @param  float   $exitPrice
     * @param  TradeType  $tradeType
     */
    public function profitLoss(float $exitPrice, TradeType $tradeType): float
    {
        $priceDiff = $exitPrice - $this->entry;

        if ($tradeType === TradeType::Sell) {
            $priceDiff = -$priceDiff;
        }

        return round($priceDiff * $this->lotSize, 4);
    }
}
