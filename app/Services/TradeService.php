<?php
namespace App\Services;

use App\Enums\TradeStatus;
use App\Enums\TransactionType;
use App\Enums\YesNo;
use App\Models\Account;
use App\Models\Trade;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TradeService
{
    /**
     * Open a new trading position.
     *
     * All financial calculations (margin, trade_fee) are delegated to
     * TradeCalculationService so there is a single source of truth.
     *
     * Steps:
     *   1. Build TradeCalculationService from form data.
     *   2. Derive margin & trade_fee from the service.
     *   3. Verify the account has enough balance.
     *   4. Inside a DB transaction:
     *      a. Create the trade record.
     *      b. Deduct margin and log the transaction.
     *      c. Deduct trade_fee and log the transaction.
     *
     * @param  array  $data      Validated form data
     * @param  int    $userId
     *
     * @throws ValidationException  When balance is insufficient.
     * @throws Exception
     */
    public function openPosition(array $data, int $userId): Trade
    {
        /** @var Account $account */
        $account = Account::findOrFail($data['account_id']);

        // ── Centralised calculations ─────────────────────────────────────────
        $calc     = TradeCalculationService::fromForm($data);
        $margin   = $calc->margin() ?? 0;
        $tradeFee = $calc->tradeFee() ?? 0;
        // ────────────────────────────────────────────────────────────────────

        $totalRequired = $margin + $tradeFee;

        // Balance check
        if ((float) $account->balance < $totalRequired) {
            throw ValidationException::withMessages([
                'account_id' => "Insufficient balance. Required: {$totalRequired}, Available: {$account->balance}",
            ]);
        }

        return DB::transaction(function () use ($data, $userId, $account, $margin, $tradeFee) {
            // 1. Create trade record
            $trade = Trade::create([
                 ...$data,
                'user_id'    => $userId,
                'status'     => TradeStatus::Open,
                'open_close' => YesNo::Yes,
                'opened_at'  => $data['opened_at'] ?? now(),
                'trade_fee'  => $tradeFee,
                'margin'     => $margin,
            ]);

            // 2. Deduct margin from account balance & log transaction
            if ($margin > 0) {
                $balanceBefore    = (float) $account->balance;
                $account->balance = $balanceBefore - $margin;
                $account->save();

                $trade->transactions()->create([
                    'account_id'     => $account->id,
                    'type'           => TransactionType::TradeOpen,
                    'amount'         => -$margin,
                    'balance_before' => $balanceBefore,
                    'balance_after'  => $account->balance,
                    'notes'          => "Margin locked for Trade #{$trade->id}",
                ]);
            }

            // 3. Deduct trade fee & log transaction
            if ($tradeFee > 0) {
                $balanceBefore    = (float) $account->balance;
                $account->balance = $balanceBefore - $tradeFee;
                $account->save();

                $trade->transactions()->create([
                    'account_id'     => $account->id,
                    'type'           => TransactionType::Fee,
                    'amount'         => -$tradeFee,
                    'balance_before' => $balanceBefore,
                    'balance_after'  => $account->balance,
                    'notes'          => "Trade open fee for Trade #{$trade->id}",
                ]);
            }

            return $trade;
        });
    }

    /**
     * Close an open trading position.
     *
     * P&L is delegated to TradeCalculationService::profitLoss() so the
     * same formula is used everywhere (form preview & actual settlement).
     *
     * Steps:
     *   1. Guard: trade must be Open.
     *   2. Build TradeCalculationService from the persisted trade.
     *   3. Derive P&L from the service.
     *   4. Inside a DB transaction:
     *      a. Update the trade record.
     *      b. Restore margin + P&L to the account balance.
     *      c. Log the close transaction.
     *
     * @throws Exception  When trade is not open.
     */
    public function closePosition(Trade $trade, float $exitPrice, ?string $notes = null): Trade
    {
        if ($trade->status !== TradeStatus::Open) {
            throw new Exception("Trade #{$trade->id} is not open.");
        }

        /** @var Account $account */
        $account = Account::findOrFail($trade->account_id);
        $margin  = (float) $trade->margin;

        // ── Centralised P&L calculation ──────────────────────────────────────
        $calc       = TradeCalculationService::fromTrade($trade);
        $tradeFee   = $calc->tradeFee(TradeStatus::Closed, $exitPrice) ?? 0;
        $profitLoss = $calc->profitLoss(
            exitPrice: $exitPrice,
            tradeType: $trade->trade_type,
        );
        // ────────────────────────────────────────────────────────────────────

        return DB::transaction(function () use ($trade, $account, $exitPrice, $margin, $tradeFee, $profitLoss, $notes) {
            // Update trade record
            $trade->update([
                'exit_price'  => $exitPrice,
                'profit_loss' => $profitLoss,
                'status'      => TradeStatus::Closed,
                'open_close'  => YesNo::No,
                'closed_at'   => now(),
                'notes'       => $notes ?? $trade->notes,
            ]);

            // Deduct trade fee & log transaction
            if ($tradeFee > 0) {
                $balanceBefore    = (float) $account->balance;
                $account->balance = $balanceBefore - $tradeFee;
                $account->save();

                $trade->transactions()->create([
                    'account_id'     => $account->id,
                    'type'           => TransactionType::Fee,
                    'amount'         => -$tradeFee,
                    'balance_before' => $balanceBefore,
                    'balance_after'  => $account->balance,
                    'notes'          => "Trade close fee for Trade #{$trade->id}",
                ]);
            }

            // Refund margin back to account balance & log transaction
            if ($margin > 0) {
                $balanceBefore    = (float) $account->balance;
                $account->balance = $balanceBefore + $margin; // ✅ Add margin
                $account->save();

                $trade->transactions()->create([
                    'account_id'     => $account->id,
                    'type'           => TransactionType::TradeClose, // ✅ Close type
                    'amount'         => $margin,                     // ✅ positive
                    'balance_before' => $balanceBefore,
                    'balance_after'  => $account->balance,
                    'notes'          => "Margin released for Trade #{$trade->id}",
                ]);
            }

            // Restore profit/loss to account balance
            $balanceBefore    = (float) $account->balance;
            $account->balance = $balanceBefore + $profitLoss;
            $account->save();

            // Log close transaction
            $trade->transactions()->create([
                'account_id'     => $account->id,
                'type'           => TransactionType::ProfitLoss,
                'amount'         => $profitLoss,
                'balance_before' => $balanceBefore,
                'balance_after'  => (float) $account->balance,
                'notes'          => "Trade #{$trade->id} closed. P&L: {$profitLoss}",
            ]);

            return $trade->fresh();
        });
    }
}
