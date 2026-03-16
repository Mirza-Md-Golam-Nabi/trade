<?php

namespace App\Filament\User\Resources\Trades;

use App\Filament\User\Resources\Trades\Pages\CreateTrade;
use App\Filament\User\Resources\Trades\Pages\EditTrade;
use App\Filament\User\Resources\Trades\Pages\ListTrades;
use App\Filament\User\Resources\Trades\Pages\ViewTrade;
use App\Filament\User\Resources\Trades\Schemas\TradeForm;
use App\Filament\User\Resources\Trades\Schemas\TradeInfolist;
use App\Filament\User\Resources\Trades\Tables\TradesTable;
use App\Models\Trade;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TradeResource extends Resource
{
    protected static ?string $model = Trade::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TradeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TradeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TradesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTrades::route('/'),
            'create' => CreateTrade::route('/create'),
            'view' => ViewTrade::route('/{record}'),
            'edit' => EditTrade::route('/{record}/edit'),
        ];
    }
}
