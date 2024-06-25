<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CantidadMat extends Model
{
    use HasFactory;

    protected $table = 'cantidadmat';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'cantidad',
        'fk_id_materiales',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'fk_id_materiales');
    }
}
