<?php

namespace App\Http\Controllers;

use App\Models\statistik;
use Illuminate\Http\Request;

class StatistikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.statistk.index');
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
    public function show(statistik $statistik)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(statistik $statistik)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, statistik $statistik)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(statistik $statistik)
    {
        //
    }
}
