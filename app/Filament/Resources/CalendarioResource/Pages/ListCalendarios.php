<?php
namespace App\Filament\Resources\CalendarioResource\Pages;

use App\Filament\Resources\CalendarioResource;
use App\Filament\Widgets\CalendarWidget;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\Page;

class ListCalendarios extends ListRecords
{
    protected static string $resource = CalendarioResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
