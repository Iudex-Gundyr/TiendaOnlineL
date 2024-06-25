<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Cliente extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id';
    protected $table = 'cliente';
    public $timestamps = false;

    protected $fillable = [
        'nombrec', 'correo', 'password', 'direccion', 'blockd', 'numerod',
        'documentacion', 'telefono', 'telefonof', 'fk_id_identificacion',
        'fk_id_ciudad', 'fk_id_estadoel'
    ];

    // Mutador para encriptar automáticamente la contraseña
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function identificacion()
    {
        return $this->belongsTo(Identificacion::class, 'fk_id_identificacion');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'fk_id_ciudad');
    }

    public function estadoEl()
    {
        return $this->belongsTo(EstadoEl::class, 'fk_id_estadoel');
    }

    // Nueva relación para acceder a país a través de ciudad y región
    public function region()
    {
        return $this->hasOneThrough(
            Region::class,
            Ciudad::class,
            'id', // Foreign key on Ciudad table...
            'id', // Foreign key on Region table...
            'fk_id_ciudad', // Local key on Cliente table...
            'fk_id_region' // Local key on Ciudad table...
        );
    }

    public function pais()
    {
        return $this->hasOneThrough(
            Pais::class,
            Region::class,
            'id', // Foreign key on Region table...
            'id', // Foreign key on Pais table...
            'fk_id_ciudad', // Local key on Cliente table...
            'fk_id_pais' // Local key on Region table...
        );
    }
}
