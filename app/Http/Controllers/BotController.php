<?php

// app/Http/Controllers/BotController.php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Model\meter;
use App\Models\Room;
use Illuminate\Support\Facades\Session;

class BotController extends Controller
{
    public function checkTenant(Request $request)
    {
        // Validate incoming data (phone number)
        Log::info('Received request data:', $request->all());
    
        $request->validate([
            'phone' => 'required|exists:tenants,phone', // Check if the phone exists in the tenants table
        ]);
    
        $tenant = Tenant::where('phone', $request->phone)->first();
    
        if ($tenant) {
            Log::info("checkTenant - Tenant found with phone: {$request->phone}");
            return response()->json(['status' => 'found', 'tenant' => $tenant]);
        } else {
            Log::warning("checkTenant - Tenant not found for phone: {$request->phone}");
            return response()->json(['status' => 'not_found']);
        }
    }
    

    public function getAvailableRooms()
    {
        // Fetch available rooms
        $rooms = Room::where('room_status', 'available')->get();
        
        // Log query results
        Log::info("Rooms query executed, result count: " . $rooms->count());
        
        // If rooms are empty, log the issue
        if ($rooms->isEmpty()) {
            Log::warning("No rooms found with room_status 'available'.");
        } else {
            Log::info("Rooms available: ", $rooms->toArray());
        }
        
        return $rooms;
    }

    
    public function checkTenantv2(Request $request)
    {
        Log::info('checkTenantv2 - Request received:', $request->all());
        try {
            $request->validate(['phone' => 'required']);
            $tenant = Tenant::where('phone', $request->phone)->first();

            if ($tenant) {
                $currentState = session("user_menu_{$request->phone}", 'mainMenu');
                Session::put("user_menu_{$request->phone}", $currentState);
                Log::info("checkTenantv2 - Tenant found with phone {$request->phone}, current state: {$currentState}");
                return response()->json([
                    'status' => 'found',
                    'message' => $this->getMenuMessage($currentState),
                    'tenant' => $tenant,
                ]);
            } else {
                Session::put("user_menu_{$request->phone}", 'notRegistered');
                Log::warning("checkTenantv2 - Tenant not registered for phone {$request->phone}");
                return response()->json([
                    'status' => 'not_found',
                    'message' => 'You are not registered as a tenant. Please contact the admin.',
                ]);
            }
        } catch (\Exception $e) {
            Log::error("checkTenantv2 - Error: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
            return response()->json(['status' => 'error', 'message' => 'An error occurred.'], 500);
        }
    }


    private function getMenuMessage($state)
    {
        Log::info("getMenuMessage - Generating menu message for state: {$state}");
        switch ($state) {
            case 'mainMenu':
                return "Welcome to the Tenant Bot! Choose an option:\n\n1. View Invoice\n2. File Complaint\n3. Contact Admin\n4. Available Room\n\nReply with the number of your choice.";
            case 'viewInvoiceMenu':
                return "You selected 'View Invoice'. Please enter the month (e.g., 'January 2025').\n\n0. Back to Main Menu";
            case 'fileComplaintMenu':
                return "You selected 'File Complaint'. Please describe your issue below.\n\n0. Back to Main Menu";
            case 'contactAdminMenu':
                return "You selected 'Contact Admin'. Our admin will reach out to you soon.\n\n0. Back to Main Menu";
            case 'availableRoomMenu':
                return "You selected 'Available Room'. Here is the list of available rooms:\n\n0. Back to Main Menu";
            case 'notRegistered':
                return "You are not registered as a tenant. Please contact the admin for assistance.";
            default:
                return "An error occurred. Returning to the main menu.\n\n1. View Invoice\n2. File Complaint\n3. Contact Admin\n4. Available Room";
        }
    }



    public function handleMessage(Request $request)
{
    Log::info('handleMessage - Request received:', $request->all());
    try {
        $phone = $request->input('phone');
        $message = trim($request->input('message'));

        if (strtolower($message) === 'clear') {
            Log::info("handleMessage - Clearing session for phone {$phone}");
            Session::forget("user_menu_{$phone}");
            return response()->json(['message' => 'Your session has been cleared.']);
        }

        $currentState = session("user_menu_$phone", 'mainMenu');
        Log::info("handleMessage - Current state for phone {$phone}: {$currentState}, message: {$message}");

        switch ($currentState) {
            case 'mainMenu':
                if ($message === '1') {
                    session(["user_menu_$phone" => 'viewInvoiceMenu']);
                    return response()->json(['message' => $this->getMenuMessage('viewInvoiceMenu')]);
                } elseif ($message === '2') {
                    session(["user_menu_$phone" => 'fileComplaintMenu']);
                    return response()->json(['message' => $this->getMenuMessage('fileComplaintMenu')]);
                } elseif ($message === '3') {
                    session(["user_menu_$phone" => 'contactAdminMenu']);
                    return response()->json(['message' => $this->getMenuMessage('contactAdminMenu')]);
                } elseif ($message === '4') {
                    session(["user_menu_$phone" => 'availableRoomMenu']);
                    return response()->json(['message' => $this->getMenuMessage('availableRoomMenu')]);
                } else {
                    return response()->json(['message' => $this->getMenuMessage('mainMenu')]);
                }

            case 'availableRoomMenu':
                if ($message === '0') {
                    session(["user_menu_$phone" => 'mainMenu']);
                    return response()->json(['message' => $this->getMenuMessage('mainMenu')]);
                } else {
                    $availableRooms = $this->getAvailableRooms();
                    
                    if ($availableRooms->isEmpty()) {
                        Log::info("No rooms available.");
                        return response()->json(['message' => "No rooms are currently available.\n\n0. Back to Main Menu"]);
                    }
                    Log::info("Room details to process: ", $availableRooms->toArray());
        
                    $roomList = "You selected 'Available Room'. Here is the list of available rooms:\n";
                    $roomMap = [];
        
                    // Log room processing
                    Log::info("Processing available rooms for phone {$phone}...");
                    foreach ($availableRooms as $index => $room) {
                        Log::info("Room " . ($index + 1) . ": Name: " . $room->name . ", Price: " . $room->price);
                        $roomList .= ($index + 1) . ". Room: {$room->name}, Price: {$room->price}\n";
                        $roomMap[$index + 1] = $room->id; // Map index to room ID
                    }
                    
                    session(["available_rooms_$phone" => $roomMap]);
        
                    // Log room list that will be sent to the user
                    Log::info("Room list to be sent to phone {$phone}: " . $roomList);
        
                    return response()->json(['message' => $roomList . "\n\n0. Back to Main Menu"]);
                }

            default:
                session(["user_menu_$phone" => 'mainMenu']);
                return response()->json(['message' => $this->getMenuMessage('mainMenu')]);
        }
    } catch (\Exception $e) {
        Log::error("handleMessage - Error: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
        return response()->json(['status' => 'error', 'message' => 'An error occurred.'], 500);
    }
}



    
    
}
