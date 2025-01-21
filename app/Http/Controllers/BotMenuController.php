<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use Illuminate\Support\Facades\Session;


class BotMenuController extends Controller
{



    
    public function getMenuOptions(Request $request)
    {
        
        // Log incoming request for troubleshooting
        Log::info('Received data from bot:', $request->all());

        $phone = $request->input('phone');
        $userInput = $request->input('user_input', '');
        $clearState = $request->input('clear_state', false);
        $state = $request->input('state', 'not_registered');

        // Clear the state if requested
        if ($userInput == 99) {
            Log::info('State cleared for phone:', ['phone' => $phone]);
            return response()->json([
                'user_state' => null,
                'response_message' => 'State has been cleared.',
                'options' => [],
            ]);
        }

        // Check if the user is registered
        $tenant = Tenant::where('phone', $phone)->first();
        $isRegistered = $tenant ? true : false;

        // Set the state based on user type
        $state = $isRegistered ? 'registered_main_menu' : 'not_registered_main_menu';

        // Dynamic room availability menu
        $availableRooms = Room::where('room_status', 'available')->get();
        $roomMenu = $this->generateRoomMenu($availableRooms);

        // Define menus for not registered users
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

            // Define menus for registered users
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

            // Room availability menu (shared)
            'room_availability' => [
                'response_message' => $roomMenu['message'],
                'options' => $roomMenu['options'],
                'responses' => $roomMenu['responses'],
            ],

            // Room details state
            'room_details' => [
                'response_message' => 'You selected a room, here are the details:',
                'options' => [],
                'responses' => [],
            ],
        ];

        // Fetch the menu based on the current state
        $menu = $menus[$state] ?? null;

        if (!$menu) {
            Log::warning('Invalid state detected.', ['state' => $state, 'phone' => $phone]);
            return response()->json([
                'user_state' => $state,
                'response_message' => 'Invalid state or input. Returning to the main menu.',
                'options' => $menus[$state]['options'] ?? [],
            ]);
        }

        // Handle user input
        $response = $menu['responses'][$userInput] ?? null;

        // If the state is room_details and the user input exists
        if ($state === 'room_availability' && $userInput) {
            // Check if userInput is a valid number
            $userInput = (int) $userInput;

            // Fetch the room data based on user input (room number)
            $selectedRoom = $roomMenu['data'][$userInput] ?? null;

            if ($selectedRoom) {
                Log::info('Room selected:', ['room_number' => $selectedRoom['room_number']]);

                return response()->json([
                    'user_state' => 'room_details',
                    'response_message' => "You selected room {$selectedRoom['room_number']} with a price of {$selectedRoom['room_price']}",
                    'data' => [
                        'room_number' => $selectedRoom['room_number'],
                        'room_price' => $selectedRoom['room_price'],
                    ],
                ]);
            } else {
                Log::info('Invalid room selection.');
                return response()->json([
                    'user_state' => 'room_availability',
                    'response_message' => "Invalid room selection. Please select a valid room.",
                    'options' => $menu['options'],
                ]);
            }
        }

        // If no response or invalid input
        if (!$response) {
            Log::info('Invalid input received.', ['input' => $userInput, 'phone' => $phone]);
            return response()->json([
                'user_state' => $state,
                'response_message' => "Invalid input. Please choose an option:\n" . $menu['response_message'],
                'options' => $menu['options'],
            ]);
        }

        // Log outgoing response, including data
        Log::info('Sending response to bot:', [
            'user_state' => $response['next_state'],
            'response_message' => $response['response'],
            'data' => $response['data'] ?? null,  // Log the data if it exists
        ]);

        // Return the response with data
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

        return [
            'message' => $roomMenu,
            'options' => $options,
            'responses' => $responses,
            'data' => $data,
        ];
    }

}

