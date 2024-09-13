<?php

namespace App\Filament\Widgets;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use App\Filament\Resources\AluguelResource;
use App\Models\Aluguel;
use App\Models\Cliente;
use App\Models\Copia;
use App\Models\Funcionario;
use App\Models\Livro;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;

class CalendarWidget extends FullCalendarWidget
{
    
    public Model | string | null $model = Aluguel::class;

    protected static ?int $sort = 5;

    public function config(): array
    {
        return [
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridWeek,dayGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    }
    public function getFormSchema(): array
    {
        return [
                Fieldset::make('Seleção')->label(__('aluguel.Seleção'))->schema([
                    Select::make('copia_id')
                    ->relationship('copia', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->identificador)
                    ->required()
                    ->label(__('aluguel.copia_id')),
                Select::make('funcionario_id')
                    ->relationship('funcionario', 'nome')
                    ->required()
                    ->label(__('aluguel.funcionario_id')),
                Select::make('cliente_id')
                    ->relationship('cliente', 'nome')
                    ->required()
                    ->label(__('aluguel.cliente_id')),
            ]),     
                Fieldset::make('')->schema([
                    Fieldset::make('Data de Entrega do Livro')->label(__('aluguel.funcionario_id'))->schema([
                        DatePicker::make('data_de_entrega')
                            ->label(__('aluguel.data_de_entrega'))
                            ->required(),
                       ]),
                        Fieldset::make('Estado do aluguel')->label(__('aluguel.Estado do aluguel'))->schema([
                            Toggle::make('entregue')
                            ->label(__('aluguel.estado'))
                            ->default(false),
                        ]),
                ])->columns(1)->columnSpanFull()
            ];
    }

    public function fetchEvents(array $fetchInfo): array
    {
        //$record 

        return Aluguel::query()
        ->where('created_at', '>=', $fetchInfo['start'])
        ->where('data_de_entrega','<=',$fetchInfo['end'])
        ->get()
        ->map(function (Aluguel $aluguel) {
            $funcionario = Funcionario::select('*')->where('id', $aluguel->funcionario_id)->withTrashed()->first();
            $cliente = Cliente::select('*')->where('id', $aluguel->cliente_id)->withTrashed()->first();
            $copia = Copia::select('*')->where('id', $aluguel->copia_id)->withTrashed()->first();
            $livro = Livro::select('*')->where('id', $copia->livro_id)->withTrashed()->first();

            return [
                'funcionario'=>$funcionario->nome,
                'cliente'=>$cliente->nome,
                'title'=> __('aluguel.Livro') . ": ".$livro->titulo . ' | COD' . $livro->id . '-' . $copia->id,
                'start'=>$aluguel->created_at,
                'end'=>$aluguel->data_da_entrega,
                'id'=>$aluguel->id,
            ];
        })->all();
    }
}

