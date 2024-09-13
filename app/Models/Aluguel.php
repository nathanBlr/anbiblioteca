<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aluguel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'alugueis';
    protected $fillable = [
        'copia_id',
        'funcionario_id',
        'cliente_id',
        'data_de_entrega',
        'estado',
        'entregue',
    ];
    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'id', 'cliente_id')->withTrashed();
    }
    public function copia(): HasOne
    {
        return $this->hasOne(Copia::class, 'id', 'copia_id')->withTrashed();
    }
    public function funcionario(): HasOne
    {
        return $this->hasOne(Funcionario::class, 'id', 'funcionario_id')->withTrashed();
    }
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->atualizarEstado();
        });
    }

    public function atualizarEstado()
    {
        $dataDeEntrega = Carbon::parse($this->data_de_entrega);
        $hoje = Carbon::today();

        if ($this->entregue) {
            $this->estado = 'Entregue';
        } elseif ($hoje->greaterThan($dataDeEntrega)) {
            $this->estado = 'Em Atraso';
        } elseif ($hoje->diffInDays($dataDeEntrega) <= 1) {
            $this->estado = 'Próximo da Entrega';
        } else {
            $this->estado = 'No Prazo';
        }
    }
}
