<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Room; // Add Room model for querying rooms
use App\Models\Tenant; // Add Tenant model for checking registration
use App\Models\Complaint;
use App\Models\TenantRoom;
use Illuminate\Validation\Rules\In;
use Illuminate\Support\Facades\Log;

class UssdBotController extends Controller
{
    /**
     * Handle incoming bot requests
     */
    public function handleMessage(Request $request)
{
    try {
        // Validate input data
        $validated = $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $phone = trim($validated['phone']);
        $message = trim($validated['message']);

        // Log incoming request (useful for debugging)
        Log::info("Received message from {$phone}: {$message}");

        // Check if the phone number exists in the tenants table
        $tenant = Tenant::where('phone', $phone)->first();

        // Determine user state and process the menu
        $response = $this->processMenu($phone, $message, $tenant ? 'registered' : 'not_registered');

        // If response contains images, return both message & images separately
        if (is_array($response) && isset($response['message']) && isset($response['images'])) {
            return response()->json([
                'status' => 'success',
                'message' => $response['message'],
                'images' => $response['images'], // Send images separately
            ]);
        }

        // If it's just a message, return normally
        return response()->json([
            'status' => 'success',
            'message' => $response,
        ]);

    } catch (\Exception $e) {
        Log::error("Error in handleMessage: " . $e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while processing the request.',
        ], 500);
    }
}


    /**
     * Process menu logic based on user type
     */
    private function processMenu($phone, $message, $userType)
    {
        // Define menu states and messages for registered and not registered users
        $menus = [
            'registered' => [
                'main' => "Welcome to our service, Registered User! Please select an option:
1. Informasi Kamar
2. Aturan & Ketentuan
3. Perhitungan Pembayaran
4. Available Rooms
5. Kontak Admin
6. Komplain
7. Invoice",
                '1' => $this->getRegisteredRoomInfo($phone),
                '2' => $this->getRules(),
                '3' => "Silakan pilih jenis pembayaran:
1. Pembayaran saat masuk kost
2. Pembayaran per bulan
3. Pembayaran saat pindah kost\n".$this->BackMainMenu_0(),
                '4' => $this->getAvailableRooms(),
                '5' => $this->getAdminContact(),
                '6' => "Silahkan Kirim Keluhan......\n".$this->BackMainMenu_0(),
                '7' => $this->getregisteredinvoice($phone),
            ],
            'not_registered' => [
                'main' => "Welcome to our service, Not Registered User! Please select an option:
1. Available Rooms
2. Aturan & Ketentuan
3. Perhitungan Pembayaran
4. Kontak Admin",
                '1' => $this->getAvailableRooms(),
                '2' => $this->getRules(),
                '3' => "Silakan pilih jenis pembayaran:
1. Pembayaran saat masuk kost
2. Pembayaran per bulan
3. Pembayaran saat pindah kost".$this->BackMainMenu_0(),
                '4' => $this->getAdminContact(),
            ]
        ];

        // Define the cache key for the user state
        $cacheKey = "user_state_{$phone}";

        // Get the current state from the cache, default to 'main' if not set
        $currentState = Cache::get($cacheKey, 'main');

        // Handle menu navigation based on the user type (registered or not)
        if ($currentState === 'main' && isset($menus[$userType][$message])) {
            // Update the user state in the cache
            Cache::put($cacheKey, $message, 300); // Cache expires after 300 seconds
            return $menus[$userType][$message];
        }
        // Check for room details only for registered users in state '4' and non-registered users in state '1'
        if ($currentState === '4' && $userType === 'registered' && $this->isValidRoomChoice($message)) {
            // If the user is registered and chooses a valid room, fetch and display room details
            return $this->getRoomDetails($message);
        }

        if ($currentState === '1' && $userType === 'not_registered' && $this->isValidRoomChoice($message)) {
            // If the user is not registered and chooses a valid room, fetch and display room details
            return $this->getRoomDetails($message);
        }
        // For "Perhitungan Pembayaran" submenu, handle the user's choice directly
        if (($currentState === '3' || $currentState === '3.1' || $currentState === '3.2' || $currentState === '3.3') 
            && in_array($message, ['1', '2', '3'])) {
            return $this->getPaymentCalculationDetails($message);
        }

        if ($currentState !== 'main' && $message === '0') {
            // Go back to the main menu
            Cache::forget($cacheKey);
            return $menus[$userType]['main'];
        }



    /**
     * For "Komplain" submenu, handle the user's choice directly
     */
    if ($currentState === '6' && !empty($message)) {    
        return $this->createComplaint($phone, $message);
    }


        // If input is invalid, return the current menu
        return "Invalid option. Please try again.\n\n" . $menus[$userType][$currentState];
    }






    /**
     * Get available rooms from the database and return the list.
     */
    private function getAvailableRooms()
    {
        // Fetch available rooms from the rooms table
        $availableRooms = Room::where('room_status', 'available')->get();

        if ($availableRooms->isEmpty()) {
            return "No rooms are available at the moment. Press 0 to go back to the main menu.";
        }

        // Generate the list of available rooms with room numbers for selection
        $roomList = "Available Rooms:";
        $roomList .= "\nPilih Kamar Untuk Melihat Data Lebih Detail.\n";
        $roomList .= "*Contoh: A7*\n";
        $roomList .= "Kamar - Harga Kamar/bulan\n";

        foreach ($availableRooms as $room) {
            // Format the price to Indonesian Rupiah format
            $formattedPrice = number_format($room->room_price, 0, ',', '.');

            $roomList .= "*{$room->room_number}* - Rp {$formattedPrice}/Bulan\n"; 
        }

        $roomList .= $this->BackMainMenu_0();

        return $roomList;
    }


    /**
     * Check if the room choice is valid.
     */
    private function isValidRoomChoice($message)
    {
        // Fetch available room numbers and convert them to lowercase
        $availableRoomNumbers = Room::where('room_status', 'available')
            ->pluck('room_number')
            ->map(fn($room) => strtolower($room))
            ->toArray();

        // Convert the input message to lowercase before checking
        return in_array(strtolower($message), $availableRoomNumbers);
    }


    /**
     * Get room details based on the selected room number.
     */
    private function getRoomDetails($roomNumber)
{
    $room = Room::where('room_number', $roomNumber)->first();

    if (!$room) {
        return [
            "message" => "Room not found. Please try again.\n\nPress 0 to go back to the main menu.",
            "images" => [],
        ];
    }

    $roomDetails = "{$room->room_number} - This is the detailed room data!\n";
    $roomDetails .= "Price: Rp." . number_format($room->room_price, 0, ',', '.') . "/Bulan\n";
    $roomDetails .= "Room Type: " . $room->room_type . "\n";
    $roomDetails .= "Type a different room number for more information.\n";
    $roomDetails .= $this->BackMainMenu_0();

    // Convert image paths to public URLs
    $images = [];
    if (!empty($room->room_images) && is_array($room->room_images)) {
        foreach ($room->room_images as $image) {
            $images[] = asset('storage/' . $image); // Generates a full URL
        }
    }

    return [
        "message" => $roomDetails,
        "images" => $images,
    ];
}


    public function getRules() {
        $message="Berikut adalah aturan dan ketentuan kost:\n
*Ketentuan untuk menjadi penghuni Kost*:
    -Mengirim foto KTP penghuni
    -No WhatsApp yang aktif
    -Orang Bali/Hindu
    -Tidak memiliki binatang peliharaan
    -Tidak memiliki anak
    -Max 2 orang (orang lain menginap harus izin admin)
    -Memiliki rekening bank online/mobile\n
*Aturan Kost*:
    -Wajib istirahat atau tidur jam 7 malam
    -Peringatan ke-2 mengharuskan penghuni pindah
    -Kebersihan depan kamar wajib dijaga
    -Tidak membuang sampah sembarangan
    -Tidak memasang paku, stiker, atau mencorat-coret tembok
    -Admin hanya memberi foto/video CCTV jika alasannya valid\n";
    $message .= $this->BackMainMenu_0();
        return $message;
    }   

    public function getAdminContact() {
        $message = "Kontak Admin:\n- 081239366793\n- 085324666777";
    
        // Capture outputs from other functions
        $message .= $this->BackMainMenu_0();
    
        return $message;
    }
    



    public function BackMainMenu_0(){
        return "\n\nKetik 0 untuk balik ke menu utama";
        //please use this function as exampled in getAdmincontac function!  
        }



    /**
     * Get room information for registered user based on their phone
     */
    private function getRegisteredRoomInfo($phone)
    {
        // Fetch the tenant by phone number
        $tenant = Tenant::where('phone', $phone)->first();
        
        if (!$tenant) {
            return "Tenant not found. Please try again.";
        }

        // Fetch rooms where the tenant is either a primary or secondary tenant
        $tenantRooms = TenantRoom::where('primary_tenant_id', $tenant->id)
            ->orWhere('secondary_tenant_id', $tenant->id)
            ->with(['room', 'meters' => function ($query) {
                $query->latest()->limit(1); // Get the latest meter record
            }])
            ->get();

        if ($tenantRooms->isEmpty()) {
            $message = "Anda belum terdaftar ke kamar!.\n";
            $message .= $this->BackMainMenu_0();
            return $message;
        }

        // Generate room information for the tenant
        $roomInfo = "Your Room Information:\n";
        foreach ($tenantRooms as $tenantRoom) {
            $room = $tenantRoom->room;
            $roomInfo .= "Room: {$room->room_number}\n";
            $roomInfo .= "Room Type: {$room->room_type}\n";
            $roomInfo .= "Price: Rp" . number_format($room->room_price, 0, ',', '.') . "\n";

            // Fetch meter details (latest electricity usage, price, etc.)
            $meter = $tenantRoom->meters->first(); // Since we limited it to 1 in the query
            if ($meter) {
                $roomInfo .= "Total KWH: {$meter->total_kwh}\n";
                $roomInfo .= "Total Price: Rp" . number_format($meter->total_price, 0, ',', '.') . "\n";
            } else {
                $roomInfo .= "No meter data available.\n";
            }

            $roomInfo .= "\n"; // Separate rooms by a line break
        }

        $roomInfo .= $this->BackMainMenu_0();
        return $roomInfo;
    }



    /**
     * Get payment calculation details based on user choice
     */
    private function getPaymentCalculationDetails($message)
    {
        switch ($message) {
            case '1':
                return $this->calculateMoveInPayment();
            case '2':
                return $this->calculateMonthlyPayment();
            case '3':
                return $this->calculateMoveOutPayment();
            default:
                return "Invalid payment type. Please select a valid option For Example A1 . \n1. Pembayaran saat masuk kost\n2. Pembayaran per bulan\n3. Pembayaran saat pindah kost";
        }
    }

    private function calculateMoveInPayment()
    {
        $message="Berikut adalah rumus pembayaran kepada calon penghuni pada saat masuk kost:\n".
            "(harga kamar) + 200.000\n\n". 
            "Penjelasan:\n".
            "200.000 adalah uang DP yang dibayar hanya sekali saat pertama kali masuk.\n\n". "Ketik 2 Atau 3 untuk ke menu lainnya";
            $message.=$this->BackMainMenu_0();
        return($message);
    }

    private function calculateMonthlyPayment()
    {
        $message="Berikut adalah perhitungan pembayaran kepada penghuni per bulan:\n".
            "(harga kamar) + (listrik)\n\n".
            "Penjelasan:\n".
            "(listrik) adalah selisih meteran awal dan akhir bulan dikalikan 2000.\n\n". "Ketik 1 Atau 3 untuk ke menu lainnya";
            $message.=$this->BackMainMenu_0();
        return($message);
    }

    private function calculateMoveOutPayment()
    {
        $message="Berikut adalah perhitungan pembayaran kepada penghuni pada saat pindah kost:\n".
            "(harga kamar) + (listrik) + (invoice tunggakan) - 200.000\n\n".
            "Penjelasan:\n".
            "(listrik) adalah selisih meteran awal bulan dan hari pindah dikalikan 2000.\n".
            "(harga kamar) dibayar penuh jika pindah pada awal bulan.\n".
            "200.000 adalah uang DP yang akan dikembalikan atau digunakan untuk perbaikan properti kost.\n\n". "Ketik 1 Atau 2 untuk ke menu lainnya";
            $message.=$this->BackMainMenu_0();

            return($message);
    }



    /**
 * Create a complaint for the user
 */
private function createComplaint($phone, $message)
{
    // Save the complaint to the database
    $tenant = Tenant::where('phone', $phone)->first();

    if (!$tenant) {
        return "Tenant not found. Please try again.";
    }

    // Create a new complaint entry in the database
    $complaint = new Complaint();
    $complaint->tenant_id = $tenant->id;
    $complaint->message = $message;
    $complaint->status = 'pending'; // Set initial status to 'pending'
    $complaint->save();

    // Return a confirmation message
    
    // Return a confirmation message along with the main menu
    return "Thank you for your complaint. It has been recorded and will be processed soon.\n\n" .
           "Returning to the main menu...\n\n" .
           $this->processMenu($phone, '0', 'registered'); // Or use 'not_registered' depending on user type
}

public function getRegisteredInvoice($phone)
{
    // Fetch the tenant by phone number
    $tenant = Tenant::where('phone', $phone)->first();

    if (!$tenant) {
        return "Tenant not found. Please try again.";
    }

    // Fetch the rooms associated with this tenant (as primary or secondary tenant)
    $tenantRooms = TenantRoom::where('primary_tenant_id', $tenant->id)
        ->orWhere('secondary_tenant_id', $tenant->id)
        ->with(['room', 'meters' => function ($query) {
            // Fetch meters within the last 12 months
            $query->where('month', '>=', now()->subYear())->orderBy('month', 'desc');
        }])
        ->get();

    if ($tenantRooms->isEmpty()) {
        $message="Anda belum didaftarkan ke kamar!.\n";
        $message.=$this->BackMainMenu_0();
        return $message;
    }

    // Generate room invoice details
    $roomInvoice = "Your Invoice:\n";
    
    foreach ($tenantRooms as $tenantRoom) {
        $room = $tenantRoom->room;
        $roomInvoice .= "Room: {$room->room_number}\n";
        $roomInvoice .= "Room Type: {$room->room_type}\n";
        $roomInvoice .= "Price: Rp" . number_format($room->room_price, 0, ',', '.') . "\n";
        $roomInvoice .= "Electricity Usage:\n";

        // Fetch meter records
        if ($tenantRoom->meters->isNotEmpty()) {
            foreach ($tenantRoom->meters as $meter) {
                $roomInvoice .= "- Billing Month: " . date('F Y', strtotime($meter->month)) . "\n";
                $roomInvoice .= "- Total KWH: {$meter->total_kwh}\n";
                $roomInvoice .= "- Price per KWH: Rp" . number_format($meter->price_per_kwh, 0, ',', '.') . "\n";
                $roomInvoice .= "- Total Electricity Cost: Rp" . number_format($meter->total_price, 0, ',', '.') . "\n";
                $roomInvoice .= "- Payment Status: " . ucfirst($meter->status) . "\n";
                $roomInvoice .= "- Payment URL: " . ($meter->pay_proof ?? 'Not available') . "\n";
                $roomInvoice .= "\n"; // Separate invoices by a line break
                
            }
        } else {
            $roomInvoice .= "No meter data available in the past year.\n";
            
        }

        $roomInvoice .= "\n"; // Separate rooms by a line break
        $roomInvoice.=$this->BackMainMenu_0();
    }

    return $roomInvoice;
}




}


