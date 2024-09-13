<?php

namespace App\Filament\Resources\LivroResource\Pages;

use App\Filament\Resources\LivroResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLivro extends ViewRecord
{
    protected static string $resource = LivroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
