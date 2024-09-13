<?php

namespace App\Filament\Widgets;

use App\Models\Aluguel;
use App\Models\Copia;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;

class StatsLivrosOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $alugados = Aluguel::query()->where('entregue', 0)->count();

        $total = Copia::query()->count();

        $disponiveis = $total - $alugados;

        $dataMes = Trend::model(Aluguel::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        $meses = $dataMes->map(fn (TrendValue $value) => $value->aggregate);

        $mesAtual = $meses[date('n') - 1];

        $dataDia = Trend::model(Aluguel::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();

        $dias = $dataDia->map(fn (TrendValue $value) => $value->aggregate);

        if(date('j') - 1 < 2){
            if(date('n') - 1 < 2){
                $data = [__('ui.anoNovo'), 'heroicon-m-sparkles', [0], 'grey'];
            } else {
                $data = $this->emprestimos($meses, 'n', __('ui.meses'));
            }
        } else {
            $data = $this->emprestimos($dias, 'j', __('ui.dias'));
        }

        return [
            Stat::make(__('ui.livrosDisponiveis'), $disponiveis)
                ->description(__('ui.doTotal') . $total)
                ->descriptionIcon('heroicon-m-book-open'),
            Stat::make(__('ui.visitasUm'), __('ui.visitasDois'))
                ->description(__('ui.visitasTres'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make(__('ui.numeroMes'), $mesAtual)
                ->description($data[0])
                ->descriptionIcon($data[1])
                ->chart([$data[2]])
                ->color($data[3]),
        ];
    }

    private function emprestimos($array, $char, $string): array 
    {
        $diferenca = $array[date($char) - 2] - $array[date($char) - 3];

        if($diferenca > 0){
            $description = __('ui.entreUltimos') . $string . __('ui.houveMais') . $diferenca . __('ui.emprestimos');
            $icon = 'heroicon-m-arrow-trending-up';
            $color = 'success';
        } elseif ($diferenca < 0) {
            $description = __('ui.entreUltimos') . $string . __('ui.houveMenos') . ($diferenca * -1) . __('ui.emprestimos');
            $icon = 'heroicon-m-arrow-trending-down';
            $color = 'danger';
        } else {
            $description = __('ui.entreUltimos') . $string . __('ui.houveMesmo');
            $icon = 'heroicon-m-check';
            $color = 'success';
        }

        return [$description, $icon, $array, $color];
    }
}
