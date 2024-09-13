<?php

namespace App\Filament\Resources\AluguelResource\Pages;

use App\Filament\Resources\AluguelResource;
use Filament\Actions;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditAluguel extends EditRecord
{
    protected static string $resource = AluguelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
