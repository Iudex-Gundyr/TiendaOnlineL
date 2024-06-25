<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privilegios extends Model
{
    use HasFactory;

    protected $table = 'privilegios';
    protected $primaryKey = 'id';
    protected $fillable = ['nombrepri', 'fk_id_estadoel'];

    public function estadoEl()
    {
        return $this->belongsTo(EstadoEl::class, 'fk_id_estadoel');
    }
}