<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivilegioUsuario extends Model
{
    protected $table = 'PRIVILEGIO_USUARIO'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id'; // Nombre de la columna de la clave primaria

    public $timestamps = false; // Indica a Eloquent que no se usan timestamps en esta tabla

    // Relación con el modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'FK_ID_USUARIO', 'ID');
    }

    // Relación con el modelo Privilegio
    public function privilegio()
    {
        return $this->belongsTo(Privilegio::class, 'FK_ID_PRIVILEGIO', 'ID');
    }
}
