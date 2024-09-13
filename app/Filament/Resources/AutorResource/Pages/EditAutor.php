<?php

namespace App\Filament\Resources\AutorResource\Pages;

use App\Filament\Resources\AutorResource;
use Filament\Actions;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditAutor extends EditRecord
{
    protected static string $resource = AutorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            RestoreAction::make()
        ];
    }
}
