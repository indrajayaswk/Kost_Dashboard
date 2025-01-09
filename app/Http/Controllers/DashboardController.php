<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard page.
     */
    public function index()
    {
        return view('admin2.Dashboard.index');
    }

    /**
     * Show the form for adding a new dashboard entry.
     */
    public function create()
    {
        // return view('admin.dashboard.dashboard_add');
    }

    /**
     * Display a specific dashboard entry.
     */
    public function show($id)
    {
        // return view('admin.dashboard.dashboard_read', compact('id'));
    }

    /**
     * Show the form for editing a dashboard entry.
     */
    public function edit($id)
    {
        // return view('admin.dashboard.dashboard_update', compact('id'));
    }

    /**
     * Handle the deletion of a dashboard entry.
     */
    public function destroy($id)
    {
        // return view('admin.dashboard.dashboard_delete', compact('id'));
    }
}
