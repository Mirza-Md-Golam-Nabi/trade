<?php
namespace App\Filament\Forms;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class TradingStrategyForm
{
    public static function fields(): array
    {
        return [
            TextInput::make('name')
                ->required(),
            Textarea::make('description')
                ->columnSpanFull()
                ->required(),
        ];
    }
}
