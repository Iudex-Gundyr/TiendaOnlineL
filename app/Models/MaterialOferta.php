<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialOferta extends Model
{
    protected $table = 'material_oferta'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'cantidad',
        'fk_id_oferta',
        'fk_id_material',
    ];
    public $timestamps = false;
    // Relación con la oferta
    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'fk_id_oferta');
    }

    // Relación con el material
    public function material()
    {
        return $this->belongsTo(Materiales::class, 'fk_id_material');
    }
}
