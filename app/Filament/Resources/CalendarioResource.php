<?php
namespace App\Filament\Resources;

use App\Filament\Resources\CalendarioResource\Pages;
use App\Filament\Widgets\CalendarWidget;
use App\Models\Aluguel;
use Filament\Resources\Resource;

class CalendarioResource extends Resource
{
    protected static ?string $model = Aluguel::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('Calendario');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('aluguel.Gerenciamento de Aluguéis');
    }

    public static function getModelLabel(): string
    {
        return __('aluguel.Alugueis');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() < 200 ? 'warning' : 'success';
    }

    // Remova ou comente o método table para ocultar a tabel

    public static function getWidgets(): array
    {
        return [
            CalendarWidget::class
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCalendarios::route('/'),
            'create' => Pages\CreateCalendario::route('/create'),
            'edit' => Pages\EditCalendario::route('/{record}/edit'),
        ];
    }
}
