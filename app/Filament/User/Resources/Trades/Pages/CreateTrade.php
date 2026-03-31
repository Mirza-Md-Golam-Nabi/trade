<?php
namespace App\Filament\User\Resources\Trades\Pages;

use App\Filament\User\Resources\Trades\TradeResource;
use App\Services\TradeService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateTrade extends CreateRecord
{
    protected static string $resource = TradeResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        $service = new TradeService;

        try {
            return $service->openPosition($data, auth()->id());
        } catch (ValidationException $e) {
            throw $e;
        }
    }
}
