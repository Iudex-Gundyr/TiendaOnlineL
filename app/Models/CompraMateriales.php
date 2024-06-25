<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraMateriales extends Model
{
    use HasFactory;

    protected $table = 'compra_materiales';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'cantidad',
        'valorcm',
        'fk_id_compra',
        'fk_id_materiales',
        'fk_id_estadoel',
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'fk_id_compra');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'fk_id_materiales');
    }

    public function estadoEl()
    {
        return $this->belongsTo(EstadoEl::class, 'fk_id_estadoel');
    }
}
