<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Direction: string implements HasLabel, HasColor {
    case Short = 'short';
    case Long  = 'long';

    public function getLabel(): string
    {
        return match ($this) {
            self::Short => 'Short',
            self::Long  => 'Long',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Short => 'success',
            self::Long  => 'primary',
        };
    }
}
