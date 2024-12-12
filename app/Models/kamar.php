<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomer_kamar', 
        'jenis_kamar', 
        'status_kamar', 
        'harga_kamar',
    ];
    public $timestamps = true; // This will use the 'created_at' and 'updated_at' columns automatically
}
