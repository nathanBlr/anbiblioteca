<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lwwcas\LaravelCountries\Models\Country;

class Autor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome_completo', 'nome_artistico', 'data_de_nascimento', 'nacionalidade'
    ];

    public function livros(): BelongsToMany
    {
        return $this->belongsToMany(Livro::class);
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'nacionalidade');
    }
}
