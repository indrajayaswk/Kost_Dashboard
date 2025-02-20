<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Meter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        // Get selected month from request or default to current month
    $selectedMonth = $request->input('filter_month', now()->format('Y-m'));
    list($year, $month) = explode('-', $selectedMonth);

    // Fetch total unpaid amount from Meter model for the selected month
    $totalUnpaid = Meter::where('status', 'unpaid')
        ->whereYear('meter_month', $year)
        ->whereMonth('meter_month', $month)
        ->sum('total_price');

    // Fetch total room prices from Room model (static, no need for filtering)
     
    $totalRoomPrices = Room::whereHas('tenantRooms.meters', function ($query) use ($year, $month) {
        $query->where('status', 'unpaid')
              ->whereYear('meter_month', $year)
              ->whereMonth('meter_month', $month);
    })->sum('room_price');
    

    // Fetch total paid and unpaid meters for the selected month
    $totalPaid = Meter::where('status', 'paid')
        ->whereYear('meter_month', $year)
        ->whereMonth('meter_month', $month)
        ->count();

    $totalUnpaidCount = Meter::where('status', 'unpaid')
        ->whereYear('meter_month', $year)
        ->whereMonth('meter_month', $month)
        ->count();

    $totalMeters = $totalPaid + $totalUnpaidCount;

    // Calculate percentages
    $paidPercentage = $totalMeters > 0 ? round(($totalPaid / $totalMeters) * 100, 2) : 0;
    $unpaidPercentage = $totalMeters > 0 ? round(($totalUnpaidCount / $totalMeters) * 100, 2) : 0;

    // Fetch total kWh for the selected month
    $totalKwhCurrentMonth = Meter::whereYear('meter_month', $year)
        ->whereMonth('meter_month', $month)
        ->sum('total_kwh');

    // Calculate total cost for the selected month (assuming price per kWh is 2000)
    $totalCostCurrentMonth = $totalKwhCurrentMonth * 2000;

        // Get the selected year from the request, default to the current year
        $selectedYear = $request->input('year', date('Y'));

        // Fetch unique years from the meter_month column for dropdown selection
        // $availableYears = Meter::selectRaw('YEAR(meter_month) as year')
        //     ->distinct()
        //     ->orderBy('year', 'desc')
        //     ->pluck('year');

        // Chart 1: Meter Payments (Paid vs Unpaid)
        $chart1_paid = [
            'chart_title' => 'Paid Meter ' . $year,
            'chart_type' => 'line',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\Meter',
            'group_by_field' => 'meter_month',
            'group_by_period' => 'month',
            'aggregate_function' => 'count',
            'filter_field' => 'meter_month',
            'filter_custom' => function ($query) use ($year) {
                $query->whereYear('meter_month', $year);
            },
            'date_format' => 'Y-m',
            'conditions' => [
                [
                    'name' => 'Paid',
                    'condition' => "status = 'paid'",
                    'color' => 'blue',
                    'fill' => true,
                ],
            ],
        ];

        $chart1_unpaid = [
            'chart_title' => 'Not Paid Meter ' . $year,
            'chart_type' => 'line',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\Meter',
            'group_by_field' => 'meter_month',
            'group_by_period' => 'month',
            'aggregate_function' => 'count',
            'filter_field' => 'meter_month',
            'filter_custom' => function ($query) use ($year) {
                $query->whereYear('meter_month', $year);
            },
            'date_format' => 'Y-m',
            'conditions' => [
                [
                    'name' => 'Unpaid',
                    'condition' => "status = 'unpaid'",
                    'color' => 'red',
                    'fill' => true,
                ],
            ],
        ];

        $chart1 = new LaravelChart($chart1_paid,$chart1_unpaid);

        $chart2_joined = [
            'chart_title' => 'Joined Tenants for ' . $year,
            'chart_type' => 'line',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\TenantRoom',
            'group_by_field' => 'start_date',
            'group_by_period' => 'month',
            'aggregate_function' => 'count',
            'filter_field' => 'start_date',
            'filter_custom' => function ($query) use ($year) {
                $query->whereYear('start_date', $year)->whereNull('end_date');
            },
            'date_format' => 'Y-m',
            'conditions' => [
                [
                    'name' => 'Joined',
                    'condition' => "end_date IS NULL",
                    'color' => 'blue',
                    'fill' => true,
                ],
            ],
        ];
        
        $chart2_left = [
            'chart_title' => 'Left Tenants for ' . $year,
            'chart_type' => 'line',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\TenantRoom',
            'group_by_field' => 'end_date',
            'group_by_period' => 'month',
            'aggregate_function' => 'count',
            'filter_field' => 'end_date',
            'filter_custom' => function ($query) use ($year) {
                $query->whereYear('end_date', $year);
            },
            'date_format' => 'Y-m',
            'conditions' => [
                [
                    'name' => 'Left',
                    'condition' => "end_date IS NOT NULL",
                    'color' => 'red',
                    'fill' => true,
                ],
            ],
        ];
        
        // Combine both charts
        $chart2 = new LaravelChart($chart2_joined, $chart2_left);
        

        return view('admin2.statistics.index', compact(
            'chart1',
            'chart2',
            'year',
            // 'availableYears',
            'totalUnpaid',
            'totalRoomPrices',
            'paidPercentage',
            'unpaidPercentage',
            'totalKwhCurrentMonth',
            'totalCostCurrentMonth'
        ));
    }

    public function generatePdf(Request $request)
{
    $selectedMonth = $request->input('filter_month', now()->format('Y-m'));
    list($year, $month) = explode('-', $selectedMonth);

    // Fetch summary statistics
    $totalUnpaid = Meter::where('status', 'unpaid')
        ->whereYear('meter_month', $year)
        ->whereMonth('meter_month', $month)
        ->sum('total_price');

    $totalRoomPrices = Room::whereHas('tenantRooms.meters', function ($query) use ($year, $month) {
        $query->where('status', 'unpaid')
              ->whereYear('meter_month', $year)
              ->whereMonth('meter_month', $month);
    })->sum('room_price');

    $totalPaid = Meter::where('status', 'paid')
        ->whereYear('meter_month', $year)
        ->whereMonth('meter_month', $month)
        ->count();

    $totalUnpaidCount = Meter::where('status', 'unpaid')
        ->whereYear('meter_month', $year)
        ->whereMonth('meter_month', $month)
        ->count();

    $totalMeters = $totalPaid + $totalUnpaidCount;
    $paidPercentage = $totalMeters > 0 ? round(($totalPaid / $totalMeters) * 100, 2) : 0;
    $unpaidPercentage = $totalMeters > 0 ? round(($totalUnpaidCount / $totalMeters) * 100, 2) : 0;

    $totalKwhCurrentMonth = Meter::whereYear('meter_month', $year)
        ->whereMonth('meter_month', $month)
        ->sum('total_kwh');

    $totalCostCurrentMonth = $totalKwhCurrentMonth * 2000;

    // Fetch table data
    $tableData = Meter::with(['tenantRoom.room'])
        ->whereYear('meter_month', $year)
        ->whereMonth('meter_month', $month)
        ->get()
        ->map(function ($meter) {
            return [
                'room_number' => $meter->tenantRoom->room->room_number,
                'kwh_number' => $meter->total_kwh,
                'room_price' => $meter->tenantRoom->room->room_price,
                'total_price' => $meter->total_price,
                'sum_total' => $meter->tenantRoom->room->room_price + $meter->total_price,
                'status' => $meter->status,
            ];
        })
        ->toArray(); // Convert collection to array for sorting

    // Custom sorting for room_number (natural order: A1, A2, B3, etc.)
    usort($tableData, function ($a, $b) {
        preg_match('/^([A-Za-z]+)(\d+)$/', $a['room_number'], $aMatch);
        preg_match('/^([A-Za-z]+)(\d+)$/', $b['room_number'], $bMatch);

        $alphaCompare = strcmp($aMatch[1], $bMatch[1]); // Compare the letter part
        if ($alphaCompare === 0) {
            return $aMatch[2] - $bMatch[2]; // Compare the number part as an integer
        }
        return $alphaCompare;
    });

    // Load the PDF view with all data
    $pdf = Pdf::loadView('admin2.statistics.pdf', compact(
        'totalUnpaid', 'totalRoomPrices', 'paidPercentage', 'unpaidPercentage',
        'totalKwhCurrentMonth', 'totalCostCurrentMonth', 'selectedMonth', 'tableData'
    ));

    return $pdf->download('statistics_report_' . $selectedMonth . '.pdf');
}

}