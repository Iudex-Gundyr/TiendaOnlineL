<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    use HasFactory;

    protected $table = 'categorias';
    protected $primaryKey = 'id';
    protected $fillable = ['nombrecat', 'fk_id_estadoel'];
    public $timestamps = false;

    public function estadoEl()
    {
        return $this->belongsTo(EstadoEl::class, 'fk_id_estadoel');
    }
}