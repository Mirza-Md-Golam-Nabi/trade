<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum YesNo: int implements HasLabel, HasColor
{
    case Yes = 1;
    case No = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::Yes => 'Yes',
            self::No => 'No',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Yes => 'success',
            self::No => 'primary',
        };
    }
}
