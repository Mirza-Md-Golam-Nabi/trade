<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum MarketType: int implements HasLabel, HasColor
{
    case Stock = 1;
    case Forex = 2;
    case Crypto = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::Stock => 'Stock',
            self::Forex => 'Forex',
            self::Crypto => 'Crypto',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Stock => 'success',
            self::Forex => 'primary',
            self::Crypto => 'info',
        };
    }
}
