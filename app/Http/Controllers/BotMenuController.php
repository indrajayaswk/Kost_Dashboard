<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BotMenuController extends Controller
{
    public function getMenuOptions(Request $request)
    {
        // Log the incoming request details
        Log::info('Laravel received a request from bot:', [
            'phone' => $request->input('phone'),
            'user_input' => $request->input('user_input', ''),
            'clear_state' => $request->input('clear_state', false),
            'state' => $request->input('state', 'not_registered'),
        ]);

        $phone = $request->input('phone');
        $userInput = $request->input('user_input', '');
        $clearState = $request->input('clear_state', false);
        $state = $request->input('state', 'not_registered');

        // Clear the state if requested
        if ($userInput == 99) {
            Log::info('Laravel clearing state for phone:', ['phone' => $phone]);
            return response()->json([
                'user_state' => null,
                'response_message' => 'State has been cleared.',
                'options' => [],
            ]);
        }

        // Check if the user is registered
        $tenant = Tenant::where('phone', $phone)->first();
        $isRegistered = $tenant ? true : false;

        // Set the state based on registration status
        $state = $isRegistered ? 'registered_main_menu' : 'not_registered_main_menu';
        Log::info('Laravel determined state:', [
            'phone' => $phone,
            'is_registered' => $isRegistered,
            'current_state' => $state,
        ]);

        // Generate room availability menu dynamically
        $availableRooms = Room::where('room_status', 'available')->get();
        $roomMenu = $this->generateRoomMenu($availableRooms);

        // Define menus
        $menus = [
            'not_registered_main_menu' => [
                'response_message' => "Welcome to Kost Cobra 10 & 11! How can we assist you?\n1. Check Available Rooms\n2. Kost Rules\n3. Payment Information\n4. Contact Admin",
                'options' => ['1', '2', '3', '4'],
                'responses' => [
                    '1' => [
                        'next_state' => 'room_availability',
                        'response' => $roomMenu['message'],
                        'data' => $roomMenu['data'],
                    ],
                    '2' => [
                        'next_state' => 'kost_rules',
                        'response' => 'Here are the rules and regulations of the kost...',
                    ],
                    '3' => [
                        'next_state' => 'payment_info',
                        'response' => "Payment Options:\n1. At Check-in\n2. Monthly\n3. At Check-out",
                    ],
                    '4' => [
                        'next_state' => 'contact_admin',
                        'response' => 'Admin Contact:\n081239366793 (Admin)\n085324666777 (Staff)',
                    ],
                ],
            ],
            'registered_main_menu' => [
                'response_message' => "Hello, registered user! How can we assist you?\n1. Check Available Rooms\n2. View Invoice\n3. Contact Admin",
                'options' => ['1', '2', '3'],
                'responses' => [
                    '1' => [
                        'next_state' => 'room_availability',
                        'response' => $roomMenu['message'],
                        'data' => $roomMenu['data'],
                    ],
                    '2' => [
                        'next_state' => 'view_invoice',
                        'response' => 'Please check your invoice in our system.',
                    ],
                    '3' => [
                        'next_state' => 'contact_admin',
                        'response' => 'Admin Contact:\n081239366793 (Admin)\n085324666777 (Staff)',
                    ],
                ],
            ],
            'room_availability' => [
                'response_message' => $roomMenu['message'],
                'options' => $roomMenu['options'],
                'responses' => $roomMenu['responses'],
            ],
        ];

        // Fetch the appropriate menu
        $menu = $menus[$state] ?? null;

        if (!$menu) {
            Log::warning('Laravel detected an invalid state:', [
                'state' => $state,
                'phone' => $phone,
            ]);
            return response()->json([
                'user_state' => $state,
                'response_message' => 'Invalid state or input. Returning to the main menu.',
                'options' => $menus[$state]['options'] ?? [],
            ]);
        }

        // Handle user input
        $response = $menu['responses'][$userInput] ?? null;

        if (!$response) {
            Log::info('Laravel received invalid input from bot:', [
                'phone' => $phone,
                'state' => $state,
                'user_input' => $userInput,
                'valid_options' => $menu['options'], // Log the valid options
            ]);
            return response()->json([
                'user_state' => $state,
                'response_message' => "Invalid input. Please choose an option:\n" . $menu['response_message'],
                'options' => $menu['options'],
            ]);
        }


        Log::info('Laravel sending response to bot:', [
            'phone' => $phone,
            'current_state' => $state,
            'next_state' => $response['next_state'],
            'response_message' => $response['response'],
            'data' => $response['data'] ?? null,
        ]);

        return response()->json([
            'user_state' => $response['next_state'],
            'response_message' => $response['response'],
            'options' => $menus[$response['next_state']]['options'] ?? [],
            'data' => $response['data'] ?? [],
        ]);
    }

    private function generateRoomMenu($availableRooms)
    {
        $roomMenu = "";
        $options = [];
        $responses = [];
        $data = [];

        if ($availableRooms->isEmpty()) {
            Log::info('Laravel found no available rooms.');
            return [
                'message' => 'No rooms are currently available.',
                'options' => ['0'],
                'responses' => [
                    '0' => ['next_state' => 'not_registered_main_menu', 'response' => 'Returning to the main menu.'],
                ],
                'data' => [],
            ];
        }

        $roomMenu = "We have the following rooms available:\n";

        foreach ($availableRooms as $index => $room) {
            $option = (string)($index + 1);
            $roomMenu .= "{$option}. Room {$room->room_number} - Price: {$room->room_price}\n";
            $options[] = $option;
            $responses[$option] = [
                'next_state' => 'room_details',
                'response' => "You selected room {$room->room_number} with a price of {$room->room_price}",
                'data' => [
                    'room_number' => $room->room_number,
                    'room_price' => $room->room_price,
                ],
            ];
            $data[$option] = [
                'room_number' => $room->room_number,
                'room_price' => $room->room_price,
            ];
        }

        Log::info('Laravel generated room menu:', ['menu' => $roomMenu]);
        Log::info('Laravel generated responses for room menu:', ['responses' => $responses]);

        return [
            'message' => $roomMenu,
            'options' => $options,
            'responses' => $responses,
            'data' => $data,
        ];
        
    }
}
