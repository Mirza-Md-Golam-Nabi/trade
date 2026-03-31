<?php

namespace App\Filament\User\Resources\Trades\Tables;

use App\Enums\TradeStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TradesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('market_type')
                    ->label('Market')
                    ->badge()
                    ->sortable(),

                TextColumn::make('tradingAsset.symbol')
                    ->label('Asset')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('account.name')
                    ->label('Account')
                    ->searchable(),

                TextColumn::make('direction')
                    ->badge()
                    ->sortable(),

                TextColumn::make('trade_type')
                    ->label('Type')
                    ->badge()
                    ->sortable(),

                TextColumn::make('entry_price')
                    ->label('Entry')
                    ->numeric(decimalPlaces: 4)
                    ->sortable(),

                TextColumn::make('exit_price')
                    ->label('Exit')
                    ->numeric(decimalPlaces: 4)
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('lot_size')
                    ->label('Lot')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('margin')
                    ->label('Margin')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('profit_loss')
                    ->label('P&L')
                    ->numeric(decimalPlaces: 2)
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('opened_at')
                    ->label('Opened')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('closed_at')
                    ->label('Closed')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('tradingStrategy.name')
                    ->label('Strategy')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('stop_loss')
                    ->label('SL')
                    ->numeric(decimalPlaces: 4)
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('take_profit')
                    ->label('TP')
                    ->numeric(decimalPlaces: 4)
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(TradeStatus::class),

                SelectFilter::make('market_type')
                    ->relationship('tradingAsset', 'symbol'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
