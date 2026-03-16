<?php

namespace App\Filament\User\Resources\Trades\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TradesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('account.name')
                    ->searchable(),
                TextColumn::make('tradingAsset.id')
                    ->searchable(),
                TextColumn::make('tradingStrategy.name')
                    ->searchable(),
                TextColumn::make('direction')
                    ->badge()
                    ->searchable(),
                TextColumn::make('trade_type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('entry_price')
                    ->money()
                    ->sortable(),
                TextColumn::make('lot_size')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('leverage')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stop_loss')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('take_profit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('profit_loss')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('risk_percent')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('exit_price')
                    ->money()
                    ->sortable(),
                TextColumn::make('notes')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('is_open')
                    ->badge()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('opened_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('is_closed')
                    ->badge()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('closed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
