<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tenants'; // Specify table name if it's not plural of the model name. ini dipake untuk botbroadcast
    protected $fillable = [
        'name',
        'phone',
        'ktp',
        'dp',
        'start_date',
        'end_date',
        'note',
    ];

    public $timestamps = true; // This will use the 'created_at' and 'updated_at' columns automatically
}