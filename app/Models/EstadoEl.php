<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoEl extends Model
{
    use HasFactory;

    protected $table = 'estadoel';
    protected $primaryKey = 'id';
    protected $fillable = ['yn'];
}