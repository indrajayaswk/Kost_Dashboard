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
        // Validate input data
        $validated = $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $phone = $validated['phone'];
        $message = trim($validated['message']);

        // Check if the phone number exists in the tenants table
        $tenant = Tenant::where('phone', $phone)->first();

        // Process menu logic based on whether the user is registered or not
        if ($tenant) {
            // Registered user
            $responseMessage = $this->processMenu($phone, $message, 'registered');
        } else {
            // Not registered user
            $responseMessage = $this->processMenu($phone, $message, 'not_registered');
        }

        return response()->json([
            'status' => 'success',
            'message' => $responseMessage,
        ]);
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
            Cache::put($cacheKey, $message, 120); // Cache expires after 120 seconds
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
        // Fetch available rooms from the rooms table (assuming `room_status` is a column in the rooms table)
        $availableRooms = Room::where('room_status', 'available')->get();

        if ($availableRooms->isEmpty()) {
            return "No rooms are available at the moment. Press 0 to go back to the main menu.";
        }

        // Generate the list of available rooms with room numbers for selection
        $roomList = "Available Rooms:";
        $roomList .= "\nPilih Kamar Untuk Melihat Data Lebih Detail.\n";
        $roomList .= "Contoh: A7\n";
        $roomList.="Kamar - Harga Kamar/bulan\n";
        foreach ($availableRooms as $room) {
            $roomList .= "*{$room->room_number}* - Rp." . $room->room_price . "/Bulan"."\n"; // Assuming room_number and room_price are columns
        }
        $roomList.=$this->BackMainMenu_0();
        

        return $roomList;
    }

    /**
     * Check if the room choice is valid.
     */
    private function isValidRoomChoice($message)
    {
        // Fetch available room numbers from the database
        $availableRoomNumbers = Room::where('room_status', 'available')->pluck('room_number')->toArray();

        // Check if the message is a valid room number
        return in_array($message, $availableRoomNumbers);
    }

    /**
     * Get room details based on the selected room number.
     */
    private function getRoomDetails($roomNumber)
    {
        // Fetch the room from the database based on the room number
        $room = Room::where('room_number', $roomNumber)->first();
        if (!$room) {
            return "Room not found. Please try again.\n\nPress 0 to go back to the main menu.";
        }

        // For now, display a placeholder message for room details
        $roomDetails = "{$room->room_number} - This is the detailed room data!\n";
        $roomDetails .= "Price: \$" . $room->room_price . "\n"; // You can add more details here later
        $roomDetails .= "Room Type: " . $room->room_type . "\n"; // You can add more details here later
        $roomDetails .= "Type different room for there information";
        $roomDetails .= $this->BackMainMenu_0();

        return $roomDetails;
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

        // Fetch the rooms associated with this tenant
        $tenantRooms = $tenant->tenantRooms;

        if ($tenantRooms->isEmpty()) {
            return "Anda Belum terdaftar ke kamar!.\n Please type 0 to exit this menu";
        }

        // Generate room information for the tenant
        $roomInfo = "Your Room Information:\n";
        foreach ($tenantRooms as $tenantRoom) {
            $room = $tenantRoom->room;
            $roomInfo .= "Room: {$room->room_number}\n";
            $roomInfo .= "Room Type: {$room->room_type}\n";
            $roomInfo .= "Price: \${$room->room_price}\n";

            // Fetch meter details (electricity usage, price, etc.)
            $meter = $tenantRoom->meters()->latest()->first(); // Get the latest meter record
            if ($meter) {
                $roomInfo .= "Total KWH: {$meter->total_kwh}\n";
                $roomInfo .= "Total Price: \${$meter->total_price}\n";
            } else {
                $roomInfo .= "No meter data available.\n";
            }

            $roomInfo .= $this->BackMainMenu_0();;
        }

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
        return "You do not have any rooms associated with your account.";
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


