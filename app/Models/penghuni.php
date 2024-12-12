<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penghuni extends Model
{
    use HasFactory;

    protected $table = 'penghunis'; // Specify table name if it's not plural of the model name.
    protected $fillable = [
        'nama', 
        'telphon', 
        'tanggal_masuk', 
        'tanggal_keluar', 
        'dp', 
        'ktp', 
        'note', // Add 'note' to the fillable array
    ];

    public $timestamps = true; // This will use the 'created_at' and 'updated_at' columns automatically
}
