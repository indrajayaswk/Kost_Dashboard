<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class TenantRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'primary_tenant_id',
        'secondary_tenant_id',
        'room_id',
        'status',
        'start_date',
        'end_date',
        'note',
    ];

    // Boot method to handle soft delete logic, room status update, and cascading delete for meters
    protected static function boot()
    {
        parent::boot();

        // Saving event: Handle status change and end_date logic
        static::saving(function ($tenantRoom) {
            // If the status is set to inactive, set the end_date and soft delete the tenant room
            if ($tenantRoom->status === 'inactive') {
                // Set end_date if it's not already set
                if (is_null($tenantRoom->end_date)) {
                    $tenantRoom->end_date = now();
                }

                // Set soft delete
                if (is_null($tenantRoom->deleted_at)) {
                    $tenantRoom->deleted_at = now();
                }

                // Set the room status to 'available'
                if ($tenantRoom->room) {
                    $tenantRoom->room->update(['room_status' => 'available']);
                }
            }
        });

        // Deleting event: Set end_date when soft deleting and set status to inactive
        static::deleting(function ($tenantRoom) {
            // If the tenant room is being soft-deleted and end_date is not set, set the end_date
            if (is_null($tenantRoom->end_date)) {
                $tenantRoom->end_date = now();
                $tenantRoom->saveQuietly();  // Save without triggering events
            }

            // Update the status of the tenant room to 'inactive'
            $tenantRoom->status = 'inactive';
            $tenantRoom->saveQuietly();  // Save without triggering events

            // Set the room status to 'available'
            if ($tenantRoom->room) {
                $tenantRoom->room->update(['room_status' => 'available']);
            }
        });
    }

    public function primaryTenant()
    {
        return $this->belongsTo(Tenant::class, 'primary_tenant_id')->withTrashed();
    }

    public function secondaryTenant()
    {
        return $this->belongsTo(Tenant::class, 'secondary_tenant_id')->withTrashed();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Add the relationship for meters
    public function meters()
    {
        return $this->hasMany(Meter::class, 'tenant_room_id');
    }
}
