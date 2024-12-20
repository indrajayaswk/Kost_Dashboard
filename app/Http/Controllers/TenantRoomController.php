<?php

namespace App\Http\Controllers;


use App\Models\Tenant; 
use App\Models\Room; 
use App\Models\TenantRoom;
use Illuminate\Http\Request;

class TenantRoomController extends Controller
{
    public function index()
    {
        // Eager load the relationships
        $tenantRooms = TenantRoom::with(['tenant', 'room'])->get();
    
        // Pass the data to the view
        return view('admin2.tenant-room.index', compact('tenantRooms'));
    }
    
    public function create()
    {
        // Get all tenants and rooms
        $tenants = Tenant::all();
        $rooms = Room::all();
    
        // Pass tenants and rooms to the view
        return view('admin2.tenant-room.tenant-room-add', compact('tenants', 'rooms'));
    }
    
    public function store(Request $request)
    {
        // Validate the incoming request
        $data = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'room_id' => 'required|exists:rooms,id',
            'status' => 'required|in:active,inactive',
        ]);

        // Store the tenant-room assignment
        TenantRoom::create($data);

        // Redirect back to the index page or wherever needed
        return redirect()->route('tenant-room.index');
    }


    public function update(Request $request, TenantRoom $tenantRoom)
    {
        $data = $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $tenantRoom->update($data);
        return $tenantRoom;
    }

    public function destroy(TenantRoom $tenantRoom)
    {
        $tenantRoom->delete();
        return response()->noContent();
    }
}
