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
        $query = Meter::query()->with(['tenantRoom.room']);

        // Apply search filtering
        if ($request->filled('search') && $request->filled('filter_by')) {
            $search = $request->search;
            $filterBy = $request->filter_by;

            if ($filterBy == 'room_number') {
                $query->whereHas('tenantRoom.room', function ($q) use ($search) {
                    $q->where('room_number', 'LIKE', "%{$search}%");
                });
            } elseif ($filterBy == 'kwh_number') {
                $query->where('kwh_number', 'LIKE', "%{$search}%");
            } elseif ($filterBy == 'meter_month') {
                $query->where('meter_month', 'LIKE', "%{$search}%");
            } elseif ($filterBy == 'total_kwh') {
                $query->where('total_kwh', '>=', (float) $search);
            }
        }

        // Paginate results
        $meters = $query->paginate(10);

        // Fetch tenant rooms for the modal
        $tenantRooms = TenantRoom::with(['primaryTenant', 'secondaryTenant', 'room'])->active()->get();

        // Fetch previous KWH for each meter efficiently
        foreach ($meters as $meter) {
            $previousMeter = Meter::where('tenant_room_id', $meter->tenant_room_id)
                ->where('meter_month', '<', $meter->meter_month)
                ->orderBy('meter_month', 'desc')
                ->first();

            $meter->previous_kwh = $previousMeter ? $previousMeter->kwh_number : 'N/A';
        }

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
            'meter_month' => 'required|date',
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
            'meter_month' => 'required|date',
            'price_per_kwh' => 'required|numeric|min:0',
        ]);

            $meter = Meter::findOrFail($id);

            // Update the meter with validated data
            $meter->update($request->only(['tenant_room_id', 'kwh_number', 'meter_month', 'price_per_kwh']));

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

    public function bulkStore(Request $request)
    {
        // Validate the input
        $validatedData = $request->validate([
            'meters' => 'required|array',
            'meters.*.tenant_room_id' => 'required|exists:tenant_rooms,id',
            'meters.*.kwh_number' => 'required|integer|min:0',
            'meters.*.price_per_kwh' => 'required|numeric|min:0',
            'meters.*.meter_month' => 'required|date',
        ]);

        // Loop through each meter data and insert into the database
        foreach ($validatedData['meters'] as $meterData) {
            Meter::create($meterData);
        }

        return redirect()->route('meter.index')->with('success', 'Meters successfully added in bulk.');
    }

    



}
