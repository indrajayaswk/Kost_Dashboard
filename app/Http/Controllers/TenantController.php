<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Tenant; // Changed to the new model name
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tenant::query();

        // Apply filters based on the request
        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('phone') && $request->phone != '') {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        if ($request->has('dp') && $request->dp != '') {
            $query->where('dp', '=', $request->dp);
        }

        if ($request->has('note') && $request->note != '') {
            $query->where('note', 'like', '%' . $request->note . '%');
        }

        // Filter by Start Date
        if ($request->has('start_start_date') && $request->start_start_date != '') {
            $query->whereDate('start_date', '>=', $request->start_start_date);
        }
        if ($request->has('end_start_date') && $request->end_start_date != '') {
            $query->whereDate('start_date', '<=', $request->end_start_date);
        }

        // Filter by End Date
        if ($request->has('start_end_date') && $request->start_end_date != '') {
            $query->whereDate('end_date', '>=', $request->start_end_date);
        }
        if ($request->has('end_end_date') && $request->end_end_date != '') {
            $query->whereDate('end_date', '<=', $request->end_end_date);
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

        if ($request->has('no_end_date') && $request->no_end_date == '1') {
            $query->whereNull('end_date');
        }

        // // Paginate results (adjust as needed)
        $tenants = $query->paginate(10);
        // // Add a default tenant for the modal
        // $defaultTenant = Tenant::first(); // Replace this logic if needed
        return view('admin2.tenant.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tenant.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data with custom error messages
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'dp' => 'required|numeric',
            'ktp' => 'nullable|file|image|max:10000', // 10MB
            'note' => 'nullable|string',
        ], [
            'ktp.max' => 'The KTP image must not exceed 2MB.', // Custom message
            'required' => ':attribute is required.',
            'numeric' => ':attribute must be a number.',
            'date' => ':attribute must be a valid date.',
            'image' => ':attribute must be an image file.',
        ]);

        // Proceed with storing data
        $tenant = new Tenant();
        $tenant->name = $request->name;
        $tenant->phone = $request->phone;
        $tenant->start_date = $request->start_date;
        $tenant->end_date = $request->end_date;
        $tenant->dp = $request->dp;
        $tenant->note = $request->note;

        if ($request->hasFile('ktp')) {
            $path = $request->file('ktp')->store('ktp_images', 'public');
            $tenant->ktp = $path;
        }

        $tenant->save();

        return redirect()->route('tenant.index')->with('success', 'Tenant added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        return view('admin.tenant.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        return view('tenant-edit', compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'dp' => 'required|numeric',
            'ktp' => 'nullable|file|image|max:10000',
            'note' => 'nullable|string',
        ]);

        $tenant = Tenant::findOrFail($id);

        // Update fields
        $tenant->name = $request->name;
        $tenant->phone = $request->phone;
        $tenant->start_date = $request->start_date;
        $tenant->end_date = $request->end_date;
        $tenant->dp = $request->dp;
        $tenant->note = $request->note;

        if ($request->hasFile('ktp')) {
            // Delete old file
            if ($tenant->ktp && Storage::exists('public/' . $tenant->ktp)) {
                Storage::delete('public/' . $tenant->ktp);
            }

            // Save the new file
            $file = $request->file('ktp');
            $path = $file->store('ktp_images', 'public');

            if ($path) {
                $tenant->ktp = $path;
            } else {
                return back()->with('error', 'Failed to upload KTP image.');
            }
        }

        $tenant->save();

        return redirect()->route('tenant.index')->with('success', 'Tenant updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        // Delete the tenant
        $tenant->delete();

        return redirect()->route('tenant.index');
    }
}
