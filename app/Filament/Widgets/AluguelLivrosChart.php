<?php

namespace App\Filament\Widgets;

use App\Models\Aluguel;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AluguelLivrosChart extends ChartWidget
{
    public function getHeading(): ?string
    {
        return __('ui.emprestimoLivro');
    }

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'mes';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        if($activeFilter ==  'mes'){
            $data = Trend::model(Aluguel::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();

            $formato = 'd';
            $por = __('ui.porDia') . date('F') . ')';

        } elseif($activeFilter == 'semana'){
            $data = Trend::model(Aluguel::class)
            ->between(
                start: now()->startOfWeek(),
                end: now()->endOfWeek(),
            )
            ->perDay()
            ->count();

            $semanaAtual = $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('d/m'));

            $formato = 'l';
            $por = __('ui.porDia') . $semanaAtual[0] . __('ui.porDiaSemana') . $semanaAtual[6] . ')';

        } else {
            $data = Trend::model(Aluguel::class)
            ->between(
                start: now()->startOfDay(),
                end: now()->endOfDay(),
            )
            ->perHour()
            ->count();

            $formato = 'H:i';
            $por = __('ui.porHora') . date('d/m/Y') . ')';
        }
 
        return [
            'datasets' => [
                [
                    'label' => __('ui.por') . $por,
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->format($formato)),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'mes' => __('ui.esteMes'),
            'semana' => __('ui.estaSemana'),
            'dia' => __('ui.hoje'),
        ];
    }
}
