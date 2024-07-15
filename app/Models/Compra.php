<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'compra';
    public $timestamps = false; // Indica a Eloquent que maneje automáticamente los timestamps

    protected $fillable = [
        'fk_id_cliente',
        'fk_id_estadoc',
        'fk_id_estadoel',
        'created_at',
        'tokenpago', // Añade created_at al array fillable para permitir asignación masiva
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'fk_id_cliente');
    }

    public function estadoC()
    {
        return $this->belongsTo(EstadoC::class, 'fk_id_estadoc');
    }

    public function estadoEl()
    {
        return $this->belongsTo(EstadoEl::class, 'fk_id_estadoel');
    }
}
