<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDeContrato extends Model
{
    use HasFactory;
    protected $table = 'tipo_de_contrato';
    protected $fillable = [
        'nome',
        'descricao'
    ];

    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class, 'tipo_de_contrato_id', 'id');
    }

}
