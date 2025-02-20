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

        // General search across multiple columns
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhere('dp', 'like', '%' . $search . '%')
                ->orWhere('note', 'like', '%' . $search . '%')
                ->orWhere('start_date', 'like', '%' . $search . '%')
                ->orWhere('end_date', 'like', '%' . $search . '%')
                ->orWhere('created_at', 'like', '%' . $search . '%')
                ->orWhere('updated_at', 'like', '%' . $search . '%');
            });
        }

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

        // Paginate results
        $tenants = $query->paginate(10);

        return view('admin2.tenant.index', compact('tenants'));
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
            'ktp' => 'required|image|mimes:jpeg,png,jpg,gif|max:10000', // 10MB
            'note' => 'nullable|string',
        ], [
            'name.required' => 'Please provide the tenant\'s name.',
            'name.string' => 'The tenant\'s name must be a valid string.',
            'name.max' => 'The tenant\'s name cannot exceed 255 characters.',
            
            'phone.required' => 'The phone number is required.',
            'phone.string' => 'The phone number must be a valid string.',
            'phone.max' => 'The phone number cannot exceed 15 characters.',
            
            'start_date.required' => 'The check-in date is required.',
            'start_date.date' => 'The check-in date must be a valid date.',
            
            'end_date.date' => 'The check-out date must be a valid date.',
            
            'dp.required' => 'The deposit amount is required.',
            'dp.numeric' => 'The deposit amount must be a valid number.',
            
            'ktp.required' => 'The KTP image is required.',
            'ktp.image' => 'The uploaded KTP must be an image file.',
            'ktp.mimes' => 'The KTP image must be in one of the following formats: jpeg, png, jpg, gif.',
            'ktp.max' => 'The KTP image must not exceed 10MB.',
            
            'note.string' => 'The note must be a valid string.',
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
            $path = $request->file('ktp')->store('ktp_images');
            $tenant->ktp = $path;
        }

        $tenant->save();

        return redirect()->route('tenant.index')->with('success', 'Tenant added successfully!');
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

    $tenant->fill($request->all());

    if ($request->hasFile('ktp')) {
        $file = $request->file('ktp');
        $path = $file->store('ktp_images');

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
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();//automaticly set end_date when soft deleted
        return redirect()->route('tenant.index')->with('success', 'Tenant soft-deleted successfully!');
    }


    /**
     * Restoring Soft-Deleted Records:
    */
    public function restore($id)
{
    $tenant = Tenant::onlyTrashed()->findOrFail($id);
    $tenant->restore();

    return redirect()->route('tenant.index')->with('success', 'Tenant restored successfully!');
}

}
