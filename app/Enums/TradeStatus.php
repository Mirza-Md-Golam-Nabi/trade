<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TradeStatus: string implements HasLabel, HasColor {
    case Open      = 'open';
    case Closed    = 'closed';
    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Open      => 'Open',
            self::Closed    => 'Closed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Open      => 'success',
            self::Closed    => 'primary',
            self::Cancelled => 'danger',
        };
    }
}
