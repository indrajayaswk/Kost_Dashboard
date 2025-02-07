<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


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
        'room_images.*' => 'image|mimes:jpeg,png,jpg,gif',
    ]);

    $imagePaths = [];
    if ($request->hasFile('room_images')) {
        foreach ($request->file('room_images') as $image) {
            $imagePaths[] = $image->store('room_images', 'public');
        }
    }

    // Create a new room record
    $room = Room::create($validated);

    // Store the image paths in the room's room_images column (assuming the column exists)
    if (!empty($imagePaths)) {
        $room->room_images = $imagePaths;
        $room->save();
    }

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
        'room_images.*' => 'image|mimes:jpeg,png,jpg,gif',
    ]);

    $room = Room::findOrFail($id);

    // Handle the images upload
    if ($request->hasFile('room_images')) {
        // Delete old images if necessary (assuming you store image paths in database)
        foreach ($room->room_images as $oldImage) {
            Storage::delete('public/' . $oldImage);
        }

        // Upload new images
        $imagePaths = [];
        foreach ($request->file('room_images') as $image) {
            $imagePaths[] = $image->store('room_images', 'public');
        }

        // Store the image paths in the room's room_images column
        $room->room_images = $imagePaths;
    }

    // Update the other room fields
    $room->update($request->only(['room_number', 'room_type', 'room_status', 'room_price']));

    return redirect()->route('room.index')->with('success', 'Room updated successfully!');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        // $room->forceDelete();  ------>perform Permenant delete
        return redirect()->route('room.index')->with('success', 'Room deleted successfully!');
    }

    /**
     * Restore softdeleted data
     */
    public function restore($id)
    {
        $room = Room::onlyTrashed()->findOrFail($id);
        $room->restore();

        return redirect()->route('room.index')->with('success', 'Room restored successfully!');
    }


// ini dipake untuk kamar kosong unregistered menu di bot
    public function availableRooms()
    {
        $rooms = Room::where('room_status', 'available')->get(['id', 'room_number']);
        return response()->json($rooms);
    }

}
