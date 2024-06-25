<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'region';
    public $timestamps = false;

    protected $fillable = [
        'nombrere', 'fk_id_pais', 'fk_id_estadoel',
    ];

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'fk_id_pais');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'fk_id_estadoel');
    }

    public function ciudades()
    {
        return $this->hasMany(Ciudad::class, 'fk_id_region');
    }
}