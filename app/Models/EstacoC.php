<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoC extends Model
{
    use HasFactory;

    protected $table = 'estadoc';
    protected $primaryKey = 'id';
    protected $fillable = ['nombreest', 'fk_id_estadoel'];

    public function estadoEl()
    {
        return $this->belongsTo(EstadoEl::class, 'fk_id_estadoel');
    }
}
