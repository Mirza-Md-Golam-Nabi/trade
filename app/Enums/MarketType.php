<?php

namespace App\Enums;

enum MarketType: int
{
    case Forex = 1;
    case Stock = 2;
    case Crypto = 3;

    public function label(): string
    {
        return match ($this) {
            self::Forex => 'Forex',
            self::Stock => 'Stock',
            self::Crypto => 'Crypto',
        };
    }
}
