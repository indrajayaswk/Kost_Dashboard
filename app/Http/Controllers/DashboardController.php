<?php

namespace App\Http\Controllers;

use App\Models\TenantRoom;
use App\Models\Tenant;
use App\Models\Room;
use App\Models\Complaint;
use App\Models\Meter;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard page.
     */
    public function index()
    {
        $activeTenantCount = $this->getActiveTenantCount();
        $availableRoomCount = $this->getAvailableRoomCount();
        $pendingComplaintCount = $this->getPendingComplaintCount();
        $latestMonthYear = $this->getLatestMonthYear();

        // Fetch recent complaints for Table Block 1
        $recentComplaints = Complaint::with(['tenant', 'room'])
            ->latest()
            ->limit(5)
            ->get();

        // Fetch recent meter readings for Table Block 2
        $recentMeters = Meter::with(['tenantRoom.primaryTenant', 'tenantRoom.room'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin2.Dashboard.index', compact(
            'activeTenantCount',
            'availableRoomCount',
            'pendingComplaintCount',
            'latestMonthYear',
            'recentComplaints',
            'recentMeters'
        ));
    }

    /**
     * Get the number of tenants that are not soft deleted.
     */
    private function getActiveTenantCount()
    {
        return Tenant::whereNull('deleted_at')->count();
    }

    /**
     * Get the number of rooms with status 'available'.
     */
    private function getAvailableRoomCount()
    {
        return Room::where('room_status', 'available')->count();
    }

    /**
     * Get the number of complaints with status 'pending'.
     */
    private function getPendingComplaintCount()
    {
        return Complaint::where('status', 'pending')->count();
    }

    /**
     * Get the most recent month from the meter table and format it.
     */
    private function getLatestMonthYear()
    {
        $latestMeterMonth = Meter::orderBy('meter_month', 'desc')->first(['meter_month']);
        return $latestMeterMonth ? Carbon::parse($latestMeterMonth->meter_month)->format('F Y') : null;
    }
}