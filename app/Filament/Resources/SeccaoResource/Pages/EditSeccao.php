<?php

namespace App\Filament\Resources\SeccaoResource\Pages;

use App\Filament\Resources\SeccaoResource;
use Filament\Actions;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditSeccao extends EditRecord
{
    protected static string $resource = SeccaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            RestoreAction::make()
        ];
    }
}
