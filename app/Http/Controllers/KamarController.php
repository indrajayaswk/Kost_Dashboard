<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kamar::query();

        // // Apply filters based on the request
        // if ($request->has('nomer_kamar') && $request->nomer_kamar != '') {
        //     $query->where('nomer_kamar', 'like', '%' . $request->nomer_kamar . '%');
        // }

        // if ($request->has('jenis_kamar') && $request->jenis_kamar != '') {
        //     $query->where('jenis_kamar', 'like', '%' . $request->jenis_kamar . '%');
        // }

        // if ($request->has('status_kamar') && $request->status_kamar != '') {
        //     $query->where('status_kamar', $request->status_kamar);
        // }

        // if ($request->has('harga_min') && $request->harga_min != '') {
        //     $query->where('harga_kamar', '>=', $request->harga_min);
        // }

        // if ($request->has('harga_max') && $request->harga_max != '') {
        //     $query->where('harga_kamar', '<=', $request->harga_max);
        // }

        // Paginate results
        $kamars = $query->paginate(10);

        return view('admin.kamar.index', compact('kamars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kamar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'nomer_kamar' => 'required|string|max:255',
            'jenis_kamar' => 'required|string|max:255',
            'status_kamar' => 'required|in:available,occupied',
            'harga_kamar' => 'required|numeric|min:0',
        ]);
    
        // Create a new kamar record
        Kamar::create($validated);

        // dd($request->all()); untuk debugging

        // Redirect back with success message
        return redirect()->route('kamar.index')->with('success', 'Kamar successfully added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kamar $kamar)
    {
        return view('admin.kamar.show', compact('kamar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kamar = Kamar::findOrFail($id); // Fetch the room by ID
        return view('kamar.edit', compact('kamar')); // Pass data to the view
    }
    
    

    
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nomer_kamar' => 'required|string|max:255|unique:kamars,nomer_kamar,' . $id,
            'jenis_kamar' => 'required|string|max:255',
            'status_kamar' => 'required|in:available,occupied',
            'harga_kamar' => 'required|numeric|min:0',
        ]);

        $kamar = Kamar::findOrFail($id);
        $kamar->nomer_kamar = $request->nomer_kamar;
        $kamar->jenis_kamar = $request->jenis_kamar;
        $kamar->status_kamar = $request->status_kamar;
        $kamar->harga_kamar = $request->harga_kamar;
        $kamar->save();

        return redirect()->route('kamar.index')->with('success', 'Kamar updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kamar $kamar)
    {
        $kamar->delete();

        return redirect()->route('kamar.index')->with('success', 'Kamar deleted successfully!');
    }
}
