<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Lwwcas\LaravelCountries\Models\Country;

class Funcionario extends Authenticatable implements FilamentUser, HasName
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'funcionarios';

    protected $fillable = [
        'nome',
        'nome_completo',
        'data_de_nascimento',
        'nacionalidade',
        'data_de_contrato',
        'foto',
        'numero_de_telemovel',
        'email',
        'morada',
        'codigo_postal',
        'redimento_salarial',
        'tipo_de_contrato_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tipo_de_contrato(): BelongsTo
    {
        return $this->belongsTo(TipoDeContrato::class, 'tipo_de_contrato_id', 'id');
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'nacionalidade');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
    public function canAccessFilament(): bool
    {
        return true; // Implement your logic for Filament access
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    public function getFilamentName(): string
    {
        return $this->name ?? '';
    }
}
