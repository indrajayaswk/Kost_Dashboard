<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Meter;
use App\Models\TenantRoom; // Import TenantRoom model
use Illuminate\Http\Request;

class MeterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Meter::query();

        // Add filters if necessary (uncomment and customize)
        // if ($request->has('meters') && $request->meters != '') {
        //     $query->where('meters', '>=', $request->meters);
        // }

        // Paginate results
        $meters = $query->paginate(10);
        // Fetch tenant rooms for the modal
        $tenantRooms = TenantRoom::with(['tenant', 'room'])->active()->get();

        // Return the view with the required data
        return view('admin2.meter.index', compact('meters', 'tenantRooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // engga dipakai, karena makenya bukan lewat url tapi dinamis
    public function create()
    {
        // Fetch available tenant rooms (including room_number)
        // $tenantRooms = TenantRoom::with('room')->get(); // Eager load the related Room model if necessary
        $tenantRooms = TenantRoom::all();
        return view('admin2.meter.create', compact('tenantRooms'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'tenant_room_id' => 'required|exists:tenant_rooms,id', // Ensure tenant_room_id exists in tenant_rooms table
            'kwh_number' => 'required|integer|min:0',
            'month' => 'required|date',
            'price_per_kwh' => 'required|numeric|min:0',
        ]);
        
        // Log validated data for debugging purposes (optional)
        Log::info('Data validated: ', $validated);
    
        // Create the record
        $meter = Meter::create($validated);
    
        // Calculate total_kwh and total_price after creating the record
        $meter->calculateTotalKwh();
        $meter->calculateTotalPrice();
    
        // Save the updated meter with calculated values
        $meter->save();
    
        // Redirect with a success message
        return redirect()->route('meter.index')->with('success', 'Meter successfully added.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Meter $meter)
    {
        return view('admin2.meter.show', compact('meter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $meter = Meter::findOrFail($id);
        // Fetch available tenant rooms to edit the meter
        $tenantRooms = TenantRoom::all();
        return view('admin2.meter.edit', compact('meter', 'tenantRooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tenant_room_id' => 'required|exists:tenant_rooms,id', // Ensure tenant_room_id exists
            'kwh_number' => 'required|integer|min:0',
            'month' => 'required|date',
            'price_per_kwh' => 'required|numeric|min:0',
        ]);

            $meter = Meter::findOrFail($id);

            // Update the meter with validated data
            $meter->update($request->only(['tenant_room_id', 'kwh_number', 'month', 'price_per_kwh']));

            // Recalculate total_kwh and total_price after updating
            $meter->calculateTotalKwh();
            $meter->calculateTotalPrice();

            // Save the updated meter with recalculated values
            $meter->save();

            return redirect()->route('meter.index')->with('success', 'Meter updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meter $meter)
    {
        $meter->delete();

        return redirect()->route('meter.index')->with('success', 'Meter deleted successfully!');
    }

    /**
     * Restoring Soft-Deleted Records:
     */
    public function restore($id)
    {
        $meter = Meter::onlyTrashed()->findOrFail($id);
        $meter->restore();

        return redirect()->route('meter.index')->with('success', 'Meter restored successfully!');
    }
}
