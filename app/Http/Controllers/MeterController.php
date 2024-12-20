<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Meter;
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

        return view('admin2.meter.index', compact('meters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin2.meter.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'meters' => 'required|integer|min:0',
            'month' => 'required|date', // This ensures month is a valid date
        ]);
    
        // Log validated data for debugging purposes (optional)
        Log::info('Data validated: ', $validated);
    
        // Create the record
        Meter::create($validated);
    
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
        $meters = Meter::findOrFail($id);
        return view('admin2.meter.edit', compact('meters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'meters' => 'required|integer|min:0',
            'month'=>'required|date'
        ]);

        $meter = Meter::findOrFail($id);
        $meter->update($request->only(['meters','month']));

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
        $tenant = Meter::onlyTrashed()->findOrFail($id);
        $tenant->restore();
    
        return redirect()->route('tenant.index')->with('success', 'Tenant restored successfully!');
    }
}
