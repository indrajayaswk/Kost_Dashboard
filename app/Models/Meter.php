<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meter extends Model
{
    use HasFactory, SoftDeletes;

    // The table associated with the model.
    protected $table = 'meters';

    // The primary key for the model.
    protected $primaryKey = 'id';

    // The attributes that are mass assignable.
    protected $fillable = [
        'tenant_room_id',
        'kwh_number',
        'total_kwh',
        'total_price',
        'price_per_kwh',
        'status',
        'pay_proof',
        'month',
    ];

    // The attributes that should be mutated to dates.
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'month'];

    // The relationship between Meter and TenantRoom
    public function tenantRoom()
    {
        return $this->belongsTo(TenantRoom::class, 'tenant_room_id');
    }

    // Calculate total_kwh based on kwh_number and previous record
    public function calculateTotalKwh()
    {
        // Assuming you want to compare the current kwh_number with the previous record
        $previousMeter = Meter::where('tenant_room_id', $this->tenant_room_id)
                              ->latest()
                              ->first();

        if ($previousMeter) {
            $this->total_kwh = $this->kwh_number - $previousMeter->kwh_number;
        } else {
            $this->total_kwh = $this->kwh_number;
        }

        // Make sure total_kwh doesn't go below 0
        $this->total_kwh = max($this->total_kwh, 0);
    }

    // Calculate total_price based on total_kwh and price_per_kwh
    public function calculateTotalPrice()
    {
        $this->total_price = $this->total_kwh * $this->price_per_kwh;
    }

    // You can also trigger these methods in events or controller logic
    // Example: Automatically calculate total_kwh and total_price before saving the model
    public static function boot()
    {
        parent::boot();

        static::saving(function ($meter) {
            // Automatically calculate total_kwh and total_price before saving
            $meter->calculateTotalKwh();
            $meter->calculateTotalPrice();
        });
    }
}
