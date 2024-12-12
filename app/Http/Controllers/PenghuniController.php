<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Penghuni;
use Illuminate\Http\Request;

class PenghuniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Penghuni::query();

    // Apply filters based on the request
    if ($request->has('nama') && $request->nama != '') {
        $query->where('nama', 'like', '%' . $request->nama . '%');
    }

    if ($request->has('telphon') && $request->telphon != '') {
        $query->where('telphon', 'like', '%' . $request->telphon . '%');
    }

    if ($request->has('dp') && $request->dp != '') {
        $query->where('dp', '=', $request->dp);
    }

    if ($request->has('note') && $request->note != '') {
        $query->where('note', 'like', '%' . $request->note . '%');
    }

    // Filter by Tanggal Masuk
    if ($request->has('start_tanggal_masuk') && $request->start_tanggal_masuk != '') {
        $query->whereDate('tanggal_masuk', '>=', $request->start_tanggal_masuk);
    }
    if ($request->has('end_tanggal_masuk') && $request->end_tanggal_masuk != '') {
        $query->whereDate('tanggal_masuk', '<=', $request->end_tanggal_masuk);
    }

    // Filter by Tanggal Keluar
    if ($request->has('start_tanggal_keluar') && $request->start_tanggal_keluar != '') {
        $query->whereDate('tanggal_keluar', '>=', $request->start_tanggal_keluar);
    }
    if ($request->has('end_tanggal_keluar') && $request->end_tanggal_keluar != '') {
        $query->whereDate('tanggal_keluar', '<=', $request->end_tanggal_keluar);
    }

    // Filter by Created At
    if ($request->has('start_created_at') && $request->start_created_at != '') {
        $query->whereDate('created_at', '>=', $request->start_created_at);
    }
    if ($request->has('end_created_at') && $request->end_created_at != '') {
        $query->whereDate('created_at', '<=', $request->end_created_at);
    }

    // Filter by Updated At
    if ($request->has('start_updated_at') && $request->start_updated_at != '') {
        $query->whereDate('updated_at', '>=', $request->start_updated_at);
    }
    if ($request->has('end_updated_at') && $request->end_updated_at != '') {
        $query->whereDate('updated_at', '<=', $request->end_updated_at);
    }
    if ($request->has('no_tanggal_keluar') && $request->no_tanggal_keluar == '1') {
        $query->whereNull('tanggal_keluar');
    }

    // Paginate results (adjust as needed)
    $penghunis = $query->paginate(10);
    // Add a default Penghuni for the modal
    $defaultPenghuni = Penghuni::first(); // Replace this logic if needed
    return view('admin.penghuni.index', compact('penghunis', 'defaultPenghuni'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.penghuni.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate the incoming data with custom error messages
    $request->validate([
        'nama' => 'required|string|max:255',
        'telphon' => 'required|string|max:15',
        'tanggal_masuk' => 'required|date',
        'tanggal_keluar' => 'nullable|date',
        'dp' => 'required|numeric',
        'ktp' => 'nullable|file|image|max:10000', //ini 10MB
        'note' => 'nullable|string',
    ], [
        'ktp.max' => 'The KTP image must not exceed 2MB.', // Custom message
        'required' => ':attribute is required.',
        'numeric' => ':attribute must be a number.',
        'date' => ':attribute must be a valid date.',
        'image' => ':attribute must be an image file.',
    ]);

    // Proceed with storing data
    $penghuni = new Penghuni();
    $penghuni->nama = $request->nama;
    $penghuni->telphon = $request->telphon;
    $penghuni->tanggal_masuk = $request->tanggal_masuk;
    $penghuni->tanggal_keluar = $request->tanggal_keluar;
    $penghuni->dp = $request->dp;
    $penghuni->note = $request->note;

    if ($request->hasFile('ktp')) {
        $path = $request->file('ktp')->store('ktp_images', 'public');
        $penghuni->ktp = $path;
    }

    $penghuni->save();

    return redirect()->route('penghuni.index')->with('success', 'Penghuni added successfully!');
}



    /**
     * Display the specified resource.
     */
    public function show(Penghuni $penghuni)
    {
        return view('admin.penghuni.show', compact('penghuni'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // ---------------------- version 2-----------------
    public function edit(Penghuni $penghuni)
{
    // Return a view with the selected penghuni data
    // return response()->json($penghuni);
    return view('penghuni-edit', compact('penghuni'));
}
// ------------------------------------
 


public function update(Request $request, $id)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'telphon' => 'required|string|max:15',
        'tanggal_masuk' => 'required|date',
        'tanggal_keluar' => 'nullable|date',
        'dp' => 'required|numeric',
        'ktp' => 'nullable|file|image|max:10000',
        'note' => 'nullable|string',
    ]);

    $penghuni = Penghuni::findOrFail($id);

    // Update fields
    $penghuni->nama = $request->nama;
    $penghuni->telphon = $request->telphon;
    $penghuni->tanggal_masuk = $request->tanggal_masuk;
    $penghuni->tanggal_keluar = $request->tanggal_keluar;
    $penghuni->dp = $request->dp;
    $penghuni->note = $request->note;

    if ($request->hasFile('ktp')) {
        // Delete old file
        if ($penghuni->ktp && Storage::exists('public/' . $penghuni->ktp)) {
            Storage::delete('public/' . $penghuni->ktp);
        }

        // Save the new file
        $file = $request->file('ktp');
        $path = $file->store('ktp_images', 'public');

        if ($path) {
            $penghuni->ktp = $path;
        } else {
            return back()->with('error', 'Failed to upload KTP image.');
        }
    }

    $penghuni->save();

    return redirect()->route('penghuni.index')->with('success', 'Penghuni updated successfully!');
}









    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penghuni $penghuni)
    {
        // Delete the penghuni
        $penghuni->delete();

        return redirect()->route('penghuni.index');
    }
}