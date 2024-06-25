<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ciudad';
    public $timestamps = false;

    protected $fillable = [
        'nombreci', 'fk_id_region', 'fk_id_estadoel',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'fk_id_region');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'fk_id_estadoel');
    }
}