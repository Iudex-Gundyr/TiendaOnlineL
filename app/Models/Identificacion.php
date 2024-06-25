<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Identificacion extends Model
{
    use HasFactory;

    protected $table = 'identificacion';
    protected $primaryKey = 'id';
    protected $fillable = ['nombreide', 'fk_id_estadoel'];

    public function estadoEl()
    {
        return $this->belongsTo(EstadoEl::class, 'fk_id_estadoel');
    }
}
