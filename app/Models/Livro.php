<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Livro extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'seccao_id', 'titulo',
        'numero_de_paginas', 'data_de_publicacao', 
        'classificacao', 'sinopse'
    ];

    public function autors(): BelongsToMany
    {
        return $this->belongsToMany(Autor::class)->withTrashed();
    } 

    public function generos(): BelongsToMany
    {
        return $this->belongsToMany(Genero::class)->withTrashed();
    }

    public function seccao(): BelongsTo
    {
        return $this->belongsTo(Seccao::class)->withTrashed();
    }

    public function copias(): HasMany
    {
        return $this->hasMany(Copia::class);
    }
}
