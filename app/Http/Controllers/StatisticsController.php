<?php

namespace App\Http\Controllers;
use App\Models\Meter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $chart_options = [
        'chart_title' => 'Meter Payments by month',
        'chart_type' => 'bar',
        'report_type' => 'group_by_string',  // Use string-based grouping
        'model' => 'App\Models\Meter',
        'group_by_field' => 'date',// Group by the 'month' column
        'group_by_period' => 'day',
        'aggregate_function' => 'count', // Count the number of payments
        'filter_days' => 30,
        'chart_color' => '54, 162, 235',
    ];

    $chart = new LaravelChart($chart_options);


    // $chart_options = [
    //     'chart_title' => 'Users by months',
    //     'report_type' => 'group_by_date',
    //     'model' => 'App\Models\User',
    //     'group_by_field' => 'created_at',
    //     'group_by_period' => 'month',
    //     'chart_type' => 'bar',
    //     'filter_field' => 'created_at',
    //     'filter_days' => 30, // show only last 30 days
    // ];

    // $chart1 = new LaravelChart($chart_options);




    return view('admin2.statistics.index', compact('chart'));
}





    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
