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
        'start_date',
        'end_date', 
        'note',
    ];

    public function primaryTenant()
    {
        return $this->belongsTo(Tenant::class, 'primary_tenant_id');
    }
    
    public function secondaryTenant()
    {
        return $this->belongsTo(Tenant::class, 'secondary_tenant_id');
    }
    

    // Define relationship to Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }   
}
