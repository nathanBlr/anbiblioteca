<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genero extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome', 'descricao'
    ];

    public function livros(): BelongsToMany
    {
        return $this->belongsToMany(Livro::class);
    }
}
