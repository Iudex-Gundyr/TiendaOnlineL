<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marcas extends Model
{
    use HasFactory;

    protected $table = 'marcas';
    protected $primaryKey = 'id';
    protected $fillable = ['nombremar', 'fk_id_estadoel'];
    public $timestamps = false;

    // Relación uno a muchos con Materiales
    public function materiales()
    {
        return $this->hasMany(Material::class, 'fk_id_marcas', 'id');
    }

    // Relación con EstadoEl
    public function estadoEl()
    {
        return $this->belongsTo(EstadoEl::class, 'fk_id_estadoel');
    }
}