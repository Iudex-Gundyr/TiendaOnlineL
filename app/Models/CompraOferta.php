<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraOferta extends Model
{
    use HasFactory;

    protected $table = 'compra_oferta';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'cantidad',
        'valor',
        'fk_id_Moferta',
        'fk_id_compra',
        'fk_id_estadoel',
        'created_at',
    ];

    public function materialOferta()
    {
        return $this->belongsTo(MaterialOferta::class, 'fk_id_Moferta');
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'fk_id_compra');
    }

    public function estadoEl()
    {
        return $this->belongsTo(EstadoEl::class, 'fk_id_estadoel');
    }
}
