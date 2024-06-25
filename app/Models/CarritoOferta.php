<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarritoOferta extends Model
{
    protected $table = 'carrito_oferta'; // Nombre de la tabla en la base de datos
    public $timestamps = false;
    protected $fillable = [
        'cantidad',
        'fk_id_cliente',
        'fk_id_materialoferta',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'fk_id_cliente', 'id');
    }

    public function materialOferta()
    {
        return $this->belongsTo(MaterialOferta::class, 'fk_id_materialoferta', 'id');
    }
}
