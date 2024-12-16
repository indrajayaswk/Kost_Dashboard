<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meter extends Model
{
    use HasFactory;
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    ///////ini dipake untuk mass assigment (bantu populasi data ke database dari seeder)
    protected $fillable = [
        'id_meters', 
        'meters',
        'month', 
    ];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_meters'; // Specify the correct primary key column

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true; // Set to true if the primary key is auto-incrementing

    /**
     * The data type of the primary key.
     *
     * @var string
     */
    protected $keyType = 'int'; // Specify the data type of the primary key

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true; // This will use the 'created_at' and 'updated_at' columns automatically
}
