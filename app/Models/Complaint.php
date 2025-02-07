<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['tenant_id', 'message', 'status'];
    protected $dates = ['deleted_at'];
    
    // Define the relationship with the Tenant model
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Define the relationship with the TenantRoom model
    public function tenantRoom()
    {
        return $this->belongsTo(TenantRoom::class, 'tenant_id', 'primary_tenant_id');
    }

    // Define the relationship with the Room model through tenant_rooms
    public function room()
    {
        return $this->hasOneThrough(Room::class, TenantRoom::class, 'primary_tenant_id', 'id', 'tenant_id', 'room_id');
    }
}
