<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiales extends Model
{
    use HasFactory;
    const CREATED_AT = 'fechac';
    const UPDATED_AT = 'fechaupd';
    protected $table = 'materiales';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombrem', 'valorm', 'codigob', 'fechac', 'fechau', 'fk_id_categorias',
        'fk_id_marcas', 'fk_id_usuariocre',
        'fk_id_usuarioupd', 'fk_id_estadoel'
    ];

    public function categorias()
    {
        return $this->belongsTo(Categorias::class, 'fk_id_categorias');
    }

    public function marcas()
    {
        return $this->belongsTo(Marcas::class, 'fk_id_marcas');
    }

    public function usuarioCre()
    {
        return $this->belongsTo(Usuarios::class, 'fk_id_usuariocre');
    }

    public function usuarioUpd()
    {
        return $this->belongsTo(Usuarios::class, 'fk_id_usuarioupd');
    }

    public function estadoEl()
    {
        return $this->belongsTo(EstadoEl::class, 'fk_id_estadoel');
    }
}