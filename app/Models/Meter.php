<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meter extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'meters';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tenant_room_id',
        'kwh_number',
        'total_kwh',
        'total_price',
        'price_per_kwh',
        'status',
        'pay_proof',
        'meter_month',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'meter_month'];

    public function tenantRoom()
    {
        return $this->belongsTo(TenantRoom::class, 'tenant_room_id');
    }

    // this is accesorry when i tried to change the column name of month in meter table to meter_month, tred to use this so i dont need to change all of the code one by one but idk, it didnt work so i change it one by one but WARNING! might some paart is still broken
    // public function getMonthAttribute()
    // {
    //     return $this->meter_month;
    // }
    /**
     * Calculate total_kwh based on the current and previous record for the same tenant_room_id.
     */
    public function calculateTotalKwh()
    {
        // Find the previous meter reading for the same tenant room_id and for the same tenant
        $previousMeter = Meter::where('tenant_room_id', $this->tenant_room_id)
                              ->where('meter_month', '<', $this->meter_month)
                              ->orderBy('meter_month', 'desc')
                              ->first();

        // If a previous record exists, calculate the difference between current and previous kwh_number
        if ($previousMeter) {
            $this->total_kwh = $this->kwh_number - $previousMeter->kwh_number;
        } else {
            // If no previous record is found (first input), set total_kwh to 0
            $this->total_kwh = 0;
        }

        // Ensure total_kwh does not go below 0 (in case of error or rollback)
        $this->total_kwh = max($this->total_kwh, 0);
    }

    /**
     * Calculate total_price based on total_kwh and price_per_kwh.
     */
    public function calculateTotalPrice()
    {
        // Calculate the total price by multiplying kwh and price per kwh
        $this->total_price = $this->total_kwh * $this->price_per_kwh;
    }

    /**
     * Automatically calculate total_kwh and total_price before saving.
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($meter) {
            // Calculate total_kwh and total_price before saving the meter
            $meter->calculateTotalKwh();
            $meter->calculateTotalPrice();
        });
    }
}
