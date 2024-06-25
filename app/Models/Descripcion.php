<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descripcion extends Model
{
    use HasFactory;

    protected $table = 'descripcion';
    protected $primaryKey = 'id';
    protected $fillable = ['nombredes', 'fk_id_estadoel','fk_id_materiales'];
    public $timestamps = false;
    public function estadoEl()
    {
        return $this->belongsTo(EstadoEl::class, 'fk_id_estadoel');
    }
    public function material()
    {
        return $this->belongsTo(Material::class, 'fk_id_materiales');
    }
}