<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meteran extends Model
{
    use HasFactory;

    protected $table = 'meterans'; // Specify table name explicitly
    protected $fillable = [
        'meteran', // Add 'meteran' to the fillable array
    ];

    public $timestamps = true; // This will use the 'created_at' and 'updated_at' columns automatically
}
