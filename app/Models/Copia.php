<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Copia extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'livro_id','numero_de_edicao', 'data_de_edicao', 'editora', 
    ];

    public function getIdentificadorAttribute()
    {
        $livro = Livro::select('*')->where('id', $this->livro_id)->withTrashed()->first();

        return $livro->titulo . ' | COD' . $this->livro_id . '-' . $this->id;
    }
}
