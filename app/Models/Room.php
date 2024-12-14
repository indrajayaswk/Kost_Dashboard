<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    ///////ini dipake untuk mass assigment (bantu populasi data ke database dari seeder)
    protected $fillable = [
        'room_number', 
        'room_type', 
        'room_status', 
        'room_price',
    ];
    public $timestamps = true; // This will use the 'created_at' and 'updated_at' columns automatically
}
