<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Tenant;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        // Paginate the complaints (10 per page) and eager load tenant, tenantRoom, and room
        $complaints = Complaint::with(['tenant', 'room'])->paginate(10);

        return view('admin2.complaints.index', compact('complaints'));
    }

    // Show the form for creating a new complaint
    public function create()
    {
        $tenants = Tenant::all();
        return view('complaints.create', compact('tenants'));
    }

    // Store a newly created complaint
    public function store(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'message' => 'required|string',
            'status' => 'required|in:unread,read,finished',
        ]);

        Complaint::create($request->all());

        return redirect()->route('complaints.index')->with('success', 'Complaint created successfully.');
    }

    // Show the form for editing the specified complaint
    public function edit(Complaint $complaint)
    {
        $tenants = Tenant::all();
        return view('complaints.edit', compact('complaint', 'tenants'));
    }

    // Update the specified complaint
    public function update(Request $request, Complaint $complaint)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'message' => 'required|string',
            'status' => 'required|in:unread,read,finished',
        ]);

        $complaint->update($request->all());

        return redirect()->route('complaints.index')->with('success', 'Complaint updated successfully.');
    }

    // Soft delete the specified complaint
    public function destroy(Complaint $complaint)
    {
        $complaint->delete();

        return redirect()->route('complaints.index')->with('success', 'Complaint deleted successfully.');
    }
    public function complete(Complaint $complaint)
{
    // Ensure the current status is not already 'completed'
    if ($complaint->status !== 'completed') {
        $complaint->status = 'completed';
        $complaint->save();
    }

    return redirect()->route('complaint.index')->with('success', 'Complaint marked as completed.');
}
}
