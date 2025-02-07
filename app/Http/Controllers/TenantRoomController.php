<?php
namespace App\Http\Controllers;
use App\Http\Requests\TenantRoomRequest;
use Illuminate\Support\Facades\Log;
use App\Models\Tenant; 
use App\Models\Room; 
use App\Models\TenantRoom;

class TenantRoomController extends Controller
{
    public function index()
    {
        // Fetch all tenants who are not assigned as primary or secondary tenants in any active tenant-rooms
        $assignedPrimaryTenantIds = TenantRoom::whereNull('deleted_at')
            ->where(function ($query) {
                $query->whereNotNull('primary_tenant_id');
            })
            ->where('status', 'active') // Ensure the status is 'active'
            ->pluck('primary_tenant_id')
            ->unique();

        $assignedSecondaryTenantIds = TenantRoom::whereNull('deleted_at')
            ->where(function ($query) {
                $query->whereNotNull('secondary_tenant_id');
            })
            ->where('status', 'active') // Ensure the status is 'active'
            ->pluck('secondary_tenant_id')
            ->unique();
        
        // Combine the arrays and ensure only unique IDs remain
        $combinedTenantIds = $assignedPrimaryTenantIds->merge($assignedSecondaryTenantIds)->unique();

        // Fetch unassigned tenants for new assignments (not assigned to any active room)
        $unassignedTenants = Tenant::whereNotIn('id', $combinedTenantIds)->get();
// -----------------only data of tenants that is not assigned-------
        // Fetch all tenants (including those already assigned) for editing purposes
        $allTenants = Tenant::all();

    // Fetch rooms excluding those assigned to active tenants
    $rooms = Room::whereDoesntHave('tenantRooms', function ($query) {
        $query->whereNull('deleted_at')
              ->where('status', 'active');
    })->get();

        // Fetch tenant-room relationships with related data
        $tenantRooms = TenantRoom::with(['primaryTenant', 'secondaryTenant', 'room'])->paginate(10);

        // Pass both assigned and unassigned tenants to the view
        return view('admin2.tenant-room.index', compact('tenantRooms', 'rooms', 'allTenants', 'unassignedTenants'));
    }



    public function store(TenantRoomRequest $request)
    {
        $data = $request->validated(); // Automatically runs the validation rules
    
        $room = \App\Models\Room::findOrFail($data['room_id']);
        if ($room->room_status === 'occupied') {
            return back()->withErrors(['room_id' => 'This room is already occupied.']);
        }
    
        $room->update(['room_status' => 'occupied']);
    
        \App\Models\TenantRoom::create($data);
    
        return redirect()->route('tenant-room.index')->with('success', 'Tenant Room created successfully.');
    }
    
    public function update(TenantRoomRequest $request, $id)
    {
        $data = $request->validated();
    
        $tenantRoom = \App\Models\TenantRoom::findOrFail($id);
    
        if ($tenantRoom->room_id !== $data['room_id']) {
            $oldRoom = \App\Models\Room::findOrFail($tenantRoom->room_id);
            $oldRoom->update(['room_status' => 'available']);
    
            $newRoom = \App\Models\Room::findOrFail($data['room_id']);
            if ($newRoom->room_status === 'occupied') {
                return back()->withErrors(['room_id' => 'This room is already occupied.']);
            }
    
            $newRoom->update(['room_status' => 'occupied']);
        }
    
        $tenantRoom->update($data);
    
        return redirect()->route('tenant-room.index')->with('success', 'Tenant Room updated successfully.');
    }

    public function show($id)
    {
        $tenantRoom = TenantRoom::with(['primaryTenant', 'secondaryTenant', 'room'])->findOrFail($id);
        return view('admin2.tenant-room.show', compact('tenantRoom'));
    }

    public function destroy(TenantRoom $tenantRoom)
    {
        $tenantRoom->delete();
        return redirect()->route('tenant-room.index')->with('success', 'Tenant-room record deleted successfully.');
    }
}
