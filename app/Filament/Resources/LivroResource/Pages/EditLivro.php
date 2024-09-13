<?php

namespace App\Filament\Resources\LivroResource\Pages;

use App\Filament\Resources\LivroResource;
use Filament\Actions;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditLivro extends EditRecord
{
    protected static string $resource = LivroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            RestoreAction::make()
        ];
    }
}
