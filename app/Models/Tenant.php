<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory, SoftDeletes; // Use the SoftDeletes trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tenants'; // Specify table name if it's not plural of the model name.
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

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at']; // Ensure deleted_at is cast as a date
}
