<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seccao extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome', 'descricao', 'corredor', 'estante', 'capacidade'
    ];

    public function livros(): HasMany
    {
        return $this->hasMany(Livro::class);
    }

    public function getLocalizacaoAttribute()
    {
        return 'Corredor ' . $this->corredor . ', Estante ' . $this->estante;
    }
}
