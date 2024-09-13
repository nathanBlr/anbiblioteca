<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'clientes';
    protected $fillable = [
        'nome',
        'nome_completo',
        'data_de_nascimento',
        'morada',
        'numero_de_telemovel',
        'email',
        'codigo_postal',
        'cartao',
        'validade_cartao',
        'nome_cartão',
       'numero_cartao',
    ];
}
