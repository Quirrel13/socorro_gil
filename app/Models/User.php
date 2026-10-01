<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Linha Adicionada
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Enums\NomeRole;

#[Fillable(['name', 'email', 'password', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements Auditable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes; // Linha Alterada
    use AuditableTrait;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
    */

    protected $auditExclude = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function temRole(NomeRole $role): bool
    {
        return $this->role?->name === $role->value;
    }

    public function role() {
        return $this->belongsTo('\App\Models\Role');
    }

    public function contaCliente() {
        return $this->hasOne('\App\Models\Conta', 'cliente_id');
    }

    public function contaGerente() {
        return $this->hasMany('\App\Models\Conta', 'gerente_id');
    }

}
