<?php

namespace App\Filament\User\Resources\Trades\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TradeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('market_type')
                    ->label('Market Type')
                    ->badge(),
                TextEntry::make('account.name')
                    ->label('Account'),
                TextEntry::make('tradingAsset.symbol')
                    ->label('Trading asset'),
                TextEntry::make('tradingStrategy.name')
                    ->label('Trading strategy'),
                TextEntry::make('direction')
                    ->badge(),
                TextEntry::make('trade_type')
                    ->badge(),
                TextEntry::make('entry_price')
                    ->formatStateUsing(fn ($state) => '৳ '.number_format($state, 2)),
                TextEntry::make('lot_size')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('leverage')
                    ->numeric(),
                TextEntry::make('stop_loss')
                    ->formatStateUsing(fn ($state) => '৳ '.number_format($state, 2))
                    ->placeholder('-'),
                TextEntry::make('take_profit')
                    ->formatStateUsing(fn ($state) => '৳ '.number_format($state, 2))
                    ->placeholder('-'),
                TextEntry::make('profit_loss')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('risk_percent')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('exit_price')
                    ->formatStateUsing(fn ($state) => '৳ '.number_format($state, 2))
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('opened_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('closed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
