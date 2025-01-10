<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tenants';
    protected $fillable = [
        'name',
        'phone',
        'ktp',
        'dp',
        'start_date',
        'end_date',
        'note', 
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    // Define relationships
    public function tenantRooms()
    {
        return $this->hasMany(TenantRoom::class, 'primary_tenant_id'); // Adjust the relationship if necessary
    }

protected static function boot()
{
    parent::boot();

    // When a tenant is soft deleted
    static::deleting(function ($tenant) {
        // Set the end_date if it's not already set
        if (is_null($tenant->end_date)) {
            $tenant->end_date = now();
            $tenant->saveQuietly(); // Save the change without triggering events
        }

        // Soft delete the tenant's related tenant rooms and meters
        foreach ($tenant->tenantRooms as $tenantRoom) {
            $tenantRoom->delete(); // This will soft delete related tenant rooms

            // Soft delete related meters for each tenant room
            foreach ($tenantRoom->meters as $meter) {
                $meter->delete(); // This will soft delete related meters
            }
        }
    });

    // Automatically set the `end_date` if saving and `end_date` is null
    static::saving(function ($tenant) {
        if (!is_null($tenant->deleted_at) && is_null($tenant->end_date)) {
            $tenant->end_date = now();
        }
    });
}

}
