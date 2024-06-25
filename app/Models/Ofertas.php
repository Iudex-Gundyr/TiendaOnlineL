<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ofertas extends Model
{
    protected $table = 'ofertas'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'fotografia',
        'nombreof',
        'fechaexp',
        'fk_id_estadoel',
    ];

    // Relación con el estadoel
    public function estadoel()
    {
        return $this->belongsTo(Estadoel::class, 'fk_id_estadoel');
    }
    public function materialOfertas()
    {
        return $this->hasMany(MaterialOferta::class, 'fk_id_oferta');
    }

    public $timestamps = false; // Desactiva los timestamps en este modelo
}