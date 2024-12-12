<?php

namespace App\Http\Controllers;

use App\Models\meteran;
use Illuminate\Http\Request;

class MeteranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = meteran::query();
        $meteran = $query->paginate(10);
        return view('admin.meteran.index');
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
    public function show(meteran $meteran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(meteran $meteran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, meteran $meteran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(meteran $meteran)
    {
        //
    }
}
