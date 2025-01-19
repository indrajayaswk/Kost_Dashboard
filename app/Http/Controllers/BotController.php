<?php

// app/Http/Controllers/BotController.php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Aoo\Model\meter;

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
            return response()->json(['status' => 'found', 'tenant' => $tenant]);
        } else {
            return response()->json(['status' => 'not_found']);
        }
    }

    public function handleMessage(Request $request)
    {
        $phoneNumber = $request->input('phone');
        $messageBody = $request->input('message');
    
        // Log the incoming phone number and message
        Log::info('Received message from phone: ' . $phoneNumber . ' with message: ' . $messageBody);
    
        // Retrieve the user's current state from the session or default to 'mainMenu'
        $currentState = session("user_state_$phoneNumber", 'mainMenu');
    
        // Log the current state
        Log::info('Current state for phone ' . $phoneNumber . ': ' . $currentState);
    
        $responseMessage = '';
    
        switch ($currentState) {
            case 'mainMenu':
                if ($messageBody === '1') {
                    session(["user_state_$phoneNumber" => 'appleMenu']);
                    session()->save(); // Force the session to be saved
                    $responseMessage = "Processing... ✅\n\nYou selected Apple-based recipes. Choose a recipe:\n\n1. Apple Pie\n2. Apple Jam\n\n0. Go Back";
                } elseif ($messageBody === '2') {
                    session(["user_state_$phoneNumber" => 'strawberryMenu']);
                    session()->save(); // Force the session to be saved
                    $responseMessage = "Processing... ✅\n\nYou selected Strawberry-based recipes. Choose a recipe:\n\n1. Strawberry Cake\n2. Strawberry Smoothie\n\n0. Go Back";
                } else {
                    $responseMessage = "Welcome! Please select a recipe category:\n\n1. Apple-based Recipes\n2. Strawberry-based Recipes";
                }
                break;
    
            case 'appleMenu':
                if ($messageBody === '0') {
                    session(["user_state_$phoneNumber" => 'mainMenu']);
                    session()->save(); // Force the session to be saved
                    $responseMessage = "Returning to the main menu. ✅\n\nPlease select a recipe category:\n\n1. Apple-based Recipes\n2. Strawberry-based Recipes";
                } elseif ($messageBody === '1') {
                    $responseMessage = "This is the Apple Pie recipe. 🍎 Please try the Strawberry-based recipes next!";
                } elseif ($messageBody === '2') {
                    $responseMessage = "This is the Apple Jam recipe. 🍏 Please try the Strawberry-based recipes next!";
                } else {
                    $responseMessage = "Invalid choice. ❌\n\nChoose an Apple-based recipe:\n\n1. Apple Pie\n2. Apple Jam\n\n0. Go Back";
                }
                break;
    
            case 'strawberryMenu':
                if ($messageBody === '0') {
                    session(["user_state_$phoneNumber" => 'mainMenu']);
                    session()->save(); // Force the session to be saved
                    $responseMessage = "Returning to the main menu. ✅\n\nPlease select a recipe category:\n\n1. Apple-based Recipes\n2. Strawberry-based Recipes";
                } elseif ($messageBody === '1') {
                    $responseMessage = "This is the Strawberry Cake recipe. 🍓 Please try the Apple-based recipes next!";
                } elseif ($messageBody === '2') {
                    $responseMessage = "This is the Strawberry Smoothie recipe. 🍹 Please try the Apple-based recipes next!";
                } else {
                    $responseMessage = "Invalid choice. ❌\n\nChoose a Strawberry-based recipe:\n\n1. Strawberry Cake\n2. Strawberry Smoothie\n\n0. Go Back";
                }
                break;
    
            default:
                // Reset to main menu if an invalid state is encountered
                session(["user_state_$phoneNumber" => 'mainMenu']);
                session()->save(); // Force the session to be saved
                $responseMessage = "An error occurred. Returning to the main menu. ❌";
        }
    
        // Log the response message
        Log::info('Response message for phone ' . $phoneNumber . ': ' . $responseMessage);
    
        // Include a unique timestamp for tracking
        $responseMessage .= "\n\n🕒 Timestamp: " . now()->toDateTimeString();
    
        return response()->json(['message' => $responseMessage]);
    }
    
     
    
}
