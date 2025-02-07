<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Room; // Assuming you have a Room model to fetch room data

class BotController_V3 extends Controller
{
    public function __construct()
    {
        config(['session.driver' => 'file']);
        config(['session.lifetime' => 120]);
        config(['session.files' => storage_path('framework/sessions')]);
    }

    public function handleBotRequest(Request $request)
{
    $phone = $request->input('phone');
    $message = $request->input('message');

    Log::info("Received request from phone: {$phone} with message: {$message}");

    // Fetch current session state
    $currentState = session("user_menu_{$phone}");
    Log::info("Session state for phone {$phone}: {$currentState}");

    // Start menu interaction or handle based on current state
    if ($currentState == null) {
        return $this->sendInitialMenu($phone);
    }

    // If the user is in the available room state, show available rooms
    if ($currentState == 'start_menu') {
        if ($message == '1') {
            return $this->showAvailableRooms($phone);
        }
        // Handle other cases, like clearing the session
        if ($message == '0') {
            session()->forget("user_menu_{$phone}");
            return response()->json(['message' => 'Session cleared. You can start again.']);
        }
    }

    // Handle dynamic submenus based on current state
    if ($currentState == 'available_room') {
        return $this->handleRoomSelection($phone, $message);
    }

    return response()->json(['message' => 'Please choose a valid option.']);
}


    // Send the initial menu options
private function sendInitialMenu($phone)
{
    // Set session state to the initial menu
    session(["user_menu_{$phone}" => 'start_menu']);

    return response()->json([
        'message' => "Welcome! Please select an option:\n1. Available Rooms\n0. Clear Session",
    ]);
}

// Show available rooms
private function showAvailableRooms($phone)
{
    // Set session state to 'available_room'
    session(["user_menu_{$phone}" => 'available_room']);

    // Fetch available rooms
    $rooms = Room::where('room_status', 'available')->get();
    
    if ($rooms->isEmpty()) {
        return response()->json(['message' => 'No rooms available at the moment.']);
    }

    $responseMessage = "Available Rooms:\n";
    foreach ($rooms as $room) {
        // Display room ID and room number for selection
        $responseMessage .= "{$room->id}. {$room->room_number} - {$room->room_price}\n";
    }

    return response()->json([
        'message' => $responseMessage,
    ]);
}


    // Handle room selection based on the user's choice
private function handleRoomSelection($phone, $roomId)
{
    // Fetch room details
    $room = Room::find($roomId);
    if (!$room) {
        return response()->json(['message' => 'Invalid room selection. Please choose a valid room.']);
    }

    $responseMessage = "Room Details:\n";
    $responseMessage .= "Name: {$room->room_number}\n";
    $responseMessage .= "Price: {$room->room_price}\n";
    $responseMessage .= "Description: {$room->room_type}\n";

    // Allow user to go back to the available rooms list
    $responseMessage .= "\nTo view more rooms, type '1'. To exit, type '0'.";

    return response()->json([
        'message' => $responseMessage,
    ]);
}

}




