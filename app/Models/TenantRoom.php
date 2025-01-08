<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
