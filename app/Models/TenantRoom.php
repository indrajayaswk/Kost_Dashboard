<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['tenant_id', 'room_id', 'status', 'start_date', 'end_date', 'note'];

    protected $dates = ['start_date', 'end_date']; 
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function meters()
    {
        return $this->hasMany(Meter::class, 'tenant_room_id');
    }
}
