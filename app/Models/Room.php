<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'room_number',
        'room_type',
        'room_status',
        'room_price',
    ];

    public $timestamps = true; // This will use the 'created_at' and 'updated_at' columns automatically

    protected $dates = ['deleted_at']; // Ensure deleted_at is cast as a date

    /**
     * Relationship: Room has many TenantRooms.
     */
    public function tenantRooms()
    {
        return $this->hasMany(TenantRoom::class);
    }

    /**
     * Boot method for cascading soft delete and status changes.
     */
    protected static function boot()
    {
        parent::boot();

        // When a Room is soft deleted, soft delete its related TenantRooms and update their end_date
        static::deleting(function ($room) {
            // Check if there are related TenantRooms
            if ($room->tenantRooms) {
                foreach ($room->tenantRooms as $tenantRoom) {
                    // Soft delete related TenantRooms
                    $tenantRoom->deleteQuietly(); // Soft delete related TenantRoom
                    $tenantRoom->end_date = now(); // Set the end_date for the TenantRoom
                    $tenantRoom->saveQuietly(); // Save the changes quietly

                    $tenantRoom->status = 'inactive';
                    $tenantRoom->saveQuietly();

                    if ($tenantRoom->room) {
                        $tenantRoom->room->update(['room_status' => 'available']);
                    }

                    // Soft delete related Meters based on tenant_room_id foreign key
                    $tenantRoom->meters()->each(function ($meter) {
                        $meter->deleteQuietly(); // Soft delete related Meter records
                    });
                    
                }
            }
        });

        // When a Room is being saved (edited), check for status change
        static::saving(function ($room) {
            // Check if the status is changing from 'occupied' to 'available'
            if ($room->isDirty('room_status') && $room->room_status === 'available') {
                // If room status is changing to 'available', update the related TenantRooms
                if ($room->tenantRooms) {
                    foreach ($room->tenantRooms as $tenantRoom) {
                        // Soft delete related TenantRooms and set their end_date
                        $tenantRoom->deleteQuietly(); // Soft delete related TenantRoom
                        $tenantRoom->status = 'inactive'; // Set the status to "inactive"
                        $tenantRoom->end_date = now(); // Set the end_date for the TenantRoom
                        $tenantRoom->saveQuietly(); // Save the changes quietly

                        // Soft delete related Meters based on tenant_room_id foreign key
                        $tenantRoom->meters()->each(function ($meter) {
                            $meter->deleteQuietly(); // Soft delete related Meter records
                        });
                    }
                }
            }
        });
    }
}
