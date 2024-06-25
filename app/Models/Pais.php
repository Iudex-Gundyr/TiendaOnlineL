<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'pais';
    public $timestamps = false;

    protected $fillable = [
        'nombrepa', 'fk_id_estadoel',
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'fk_id_estadoel');
    }

    public function regiones()
    {
        return $this->hasMany(Region::class, 'fk_id_pais');
    }
}