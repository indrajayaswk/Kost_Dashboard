<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\Models\Tenant; 
use App\Models\Room; 
use App\Models\TenantRoom;
use Illuminate\Http\Request;

class TenantRoomController extends Controller
{
    public function index()
    {
        // Eager load the relationships
        $tenantRooms = TenantRoom::with(['primaryTenant', 'secondaryTenant', 'room'])->paginate(10);
        $rooms = Room::all();
        $tenants = Tenant::all(); // Fetch all tenants
    
        // Pass the data to the view
        return view('admin2.tenant-room.index', compact('tenantRooms', 'rooms', 'tenants'));
    }
    
    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $data = $request->validate([
                'primary_tenant_id' => 'required|exists:tenants,id',
                'secondary_tenant_id' => 'nullable|exists:tenants,id|different:primary_tenant_id',
                'room_id' => 'required|exists:rooms,id',
                'status' => 'required|in:active,inactive',
                'note' => 'required|string|max:255', // Note is required
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date', // End date is optional
            ]);
    
            // Store the tenant-room assignment
            TenantRoom::create($data);
    
            return redirect()->route('tenant-room.index')->with('success', 'Tenant assigned to room successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error storing tenant room: ' . $e->getMessage());
    
            // Return with an error message
            return redirect()->route('tenant-room.index')->with('error', 'An error occurred while assigning the tenant to the room.');
        }
    }

public function show($id)
{
    $tenantRoom = TenantRoom::with(['primaryTenant', 'secondaryTenant', 'room'])->findOrFail($id);
    return view('tenant-rooms.show', compact('tenantRoom'));
}


public function update(Request $request, TenantRoom $tenantRoom)
{
    try {
        // Validate the incoming request
        $data = $request->validate([
            'primary_tenant_id' => 'required|exists:tenants,id',
            'secondary_tenant_id' => 'nullable|exists:tenants,id|different:primary_tenant_id',
            'room_id' => 'required|exists:rooms,id',
            'status' => 'required|in:active,inactive',
            'note' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date', // Ensure end_date is after start_date
        ]);
        // dd($data);
        // Update the tenant-room record
        $tenantRoom->update($data);

        return redirect()->route('tenant-room.index')->with('success', 'Tenant-room record updated successfully.');
    } catch (\Exception $e) {
        // Log the error
        Log::error('Error updating tenant room: ' . $e->getMessage());

        // Return with an error message
        return redirect()->route('tenant-room.index')->with('error', 'An error occurred while updating the tenant-room record.');
    }
}

    public function destroy(TenantRoom $tenantRoom)
    {
        $tenantRoom->delete();
        return redirect()->route('tenant-room.index')->with('success', 'Tenant-room record deleted successfully.');
    }
}
