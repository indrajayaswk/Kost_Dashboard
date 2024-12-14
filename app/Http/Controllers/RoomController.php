<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Room::query();

        // Apply filters based on the request (uncomment if needed)
        // if ($request->has('room_number') && $request->room_number != '') {
        //     $query->where('room_number', 'like', '%' . $request->room_number . '%');
        // }

        // if ($request->has('room_type') && $request->room_type != '') {
        //     $query->where('room_type', 'like', '%' . $request->room_type . '%');
        // }

        // if ($request->has('room_status') && $request->room_status != '') {
        //     $query->where('room_status', $request->room_status);
        // }

        // if ($request->has('price_min') && $request->price_min != '') {
        //     $query->where('room_price', '>=', $request->price_min);
        // }

        // if ($request->has('price_max') && $request->price_max != '') {
        //     $query->where('room_price', '<=', $request->price_max);
        // }

        // Paginate results
        $rooms = $query->paginate(10);

        return view('admin2.room.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin2.room.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'room_number' => 'required|string|max:255',
            'room_type' => 'required|string|max:255',
            'room_status' => 'required|in:available,occupied',
            'room_price' => 'required|numeric|min:0',
        ]);
    
        // Create a new room record
        Room::create($validated);

        // Redirect back with success message
        return redirect()->route('room.index')->with('success', 'Room successfully added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return view('admin2.room.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $room = Room::findOrFail($id);
        return view('admin2.room.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'room_number' => 'required|string|max:255|unique:rooms,room_number,' . $id,
            'room_type' => 'required|string|max:255',
            'room_status' => 'required|in:available,occupied',
            'room_price' => 'required|numeric|min:0',
        ]);

        $room = Room::findOrFail($id);
        $room->update($request->only(['room_number', 'room_type', 'room_status', 'room_price']));

        return redirect()->route('room.index')->with('success', 'Room updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('room.index')->with('success', 'Room deleted successfully!');
    }
}
