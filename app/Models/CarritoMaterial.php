<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarritoMaterial extends Model
{
    protected $table = 'carrito_material'; // Nombre de la tabla en la base de datos
    public $timestamps = false;
    protected $fillable = [
        'cantidad',
        'fk_id_cliente',
        'fk_id_material',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'fk_id_cliente', 'id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'fk_id_material', 'id');
    }
}
