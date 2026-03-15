<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RiskLevel: string implements HasLabel, HasColor {
    case Low    = 'log';
    case Medium = 'medium';
    case High   = 'high';

    public function getLabel(): string
    {
        return match ($this) {
            self::Low    => 'Low',
            self::Medium => 'Medium',
            self::High   => 'High',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Low    => 'success',
            self::Medium => 'primary',
            self::High   => 'info',
        };
    }
}
