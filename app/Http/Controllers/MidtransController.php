<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;
use App\Models\TenantRoom;
use App\Models\Meter;
use Carbon\Carbon;
class MidtransController extends Controller
{
    public function __construct()
    {
        // Set your Midtrans server key and other configurations
        Config::$serverKey = env('MIDTRANS_SERVER_KEY'); // Your Midtrans Server Key
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false); // Set to true for production
        Config::$isSanitized = true; // Enable sanitization
        Config::$is3ds = true; // Enable 3DS for credit card payments
    }

    // Display the Midtrans payment page (existing)
    public function index(Request $request)
    {
        // Fetch all active tenant rooms with their room and primary tenant
        $tenantRooms = TenantRoom::with(['room', 'primaryTenant'])
            ->where('status', 'active') // Only show active tenant rooms
            ->get();
    
        // Fetch meters with filters
        $meters = Meter::with(['tenantRoom.room', 'tenantRoom.primaryTenant'])
            ->when($request->filled('tenant_room_id'), function ($query) use ($request) {
                $query->where('tenant_room_id', $request->tenant_room_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('meter_month'), function ($query) use ($request) {
                // Parse the input month and get the start and end of the month
                $month = \Carbon\Carbon::parse($request->meter_month);
                $startOfMonth = $month->startOfMonth()->toDateTimeString();
                $endOfMonth = $month->endOfMonth()->toDateTimeString();
    
                // Filter records where meter_month is between the start and end of the selected month
                $query->whereBetween('meter_month', [$startOfMonth, $endOfMonth]);
            })
            ->orderBy('meter_month', 'desc')
            ->paginate(10);
    
        return view('admin2.midtrans.index', [
            'tenantRooms' => $tenantRooms,
            'meters' => $meters
        ]);
    }

    // Show meter data related to the selected tenant room (existing)
    public function showMeter(Request $request)
    {
        try {
            $tenantRoom = TenantRoom::with('primaryTenant', 'room')->findOrFail($request->tenant_room_id);
    
            // Fetch the meters related to the selected tenant room
            $meters = Meter::where('tenant_room_id', $request->tenant_room_id)->get();
    
            return view('admin2.midtrans.components.midtrans-add', [
                'tenantRoom' => $tenantRoom,
                'meters' => $meters,
            ]);
        } catch (\Exception $e) {
            return view('admin2.midtrans.index', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function createInvoice(Request $request)
{
    try {
        // Validate the request data
        $data = $request->validate([
            'tenant_room_id' => 'required|exists:tenant_rooms,id',
            'meter_id' => 'required|exists:meters,id',
        ]);

        // Find the tenant room and meter data
        $tenantRoom = TenantRoom::with(['primaryTenant', 'room'])->findOrFail($data['tenant_room_id']);
        $meter = Meter::findOrFail($data['meter_id']);

        // Extract tenant and room data
        $tenant = $tenantRoom->primaryTenant;
        $room = $tenantRoom->room;

        // Prepare the payment parameters
        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $room->room_number . '-' . $meter->meter_month . '-' . $meter->id,
                'gross_amount' => $room->room_price + $meter->total_price,
            ],
            'customer_details' => [
                'first_name' => $tenant->name,
                'email' => 'akunawur2@gmail.com', // Replace with actual tenant email if available
                'phone' => $tenant->phone,
            ],
            'item_details' => [
                ['id' => 'Room_price', 'price' => $room->room_price, 'quantity' => 1, 'name' => 'Room Price'],
                ['id' => 'Electricity', 'price' => $meter->total_price, 'quantity' => 1, 'name' => 'Electricity Usage'],
            ],
            'expiry' => [
                'start_time' => now()->addMonth()->format('Y-m-d H:i:s') . ' +0700',
                'unit' => 'minutes',
                'duration' => 60,
            ],
        ];

        // Generate Snap Token and Payment Link
        $snapToken = Snap::getSnapToken($params);
        $paymentLink = 'https://app.sandbox.midtrans.com/snap/v4/redirection/' . $snapToken;

        // Update the meter's pay_proof and status
        $meter->update([
            'pay_proof' => $paymentLink,
            'status' => 'unpaid', // Set status to 'unpaid' since the payment link is generated
        ]);

        // Redirect to the index route with a success message
        return redirect()->route('midtrans.index')
            ->with('success', 'Payment link generated successfully!');

    } catch (\Exception $e) {
        // Redirect to the index route with an error message
        return redirect()->route('midtrans.index')
            ->with('error', 'Failed to generate payment link: ' . $e->getMessage());
    }
}
    


    //----------------comment and uncomment when finished conencting new url in midtrans-------------
    public function handleWebhook(Request $request)
{
    Log::info('Webhook Received:');

    // Log the raw payload for debugging
    $rawData = file_get_contents('php://input');
    Log::info('Raw Data:', ['raw_data' => $rawData]);

    // Decode the JSON payload
    $payload = json_decode($rawData, true);

    // Retrieve the signature key from the payload
    $signatureKey = $payload['signature_key'] ?? null;

    // Validate the signature key
    if (empty($signatureKey)) {
        Log::error('Missing signature_key in payload');
        return response()->json(['status' => 'error', 'message' => 'Missing signature_key'], 403);
    }

    // Verify the webhook signature for security
    if (!$this->verifyWebhookSignature($rawData, $signatureKey)) {
        Log::error('Invalid webhook signature');
        Log::info('Expected Signature:', ['expected' => hash('sha512', $rawData . env('MIDTRANS_SERVER_KEY'))]);
        Log::info('Received Signature:', ['received' => $signatureKey]);
        Log::info('Raw Data for Signature:', ['raw_data' => $rawData]);
        return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
    }

    // Process the webhook notification
    try {
        $transactionStatus = $payload['transaction_status'];
        $orderId = $payload['order_id'];

        Log::info('Transaction Status: ' . $transactionStatus);
        Log::info('Order ID: ' . $orderId);

        // Extract meter ID from the order_id format: ORDER-room_number-timestamp-meter_id
        $parts = explode('-', $orderId);

        if (count($parts) < 5) {
            Log::error('Invalid order_id format: ' . $orderId);
            return response()->json(['status' => 'error', 'message' => 'Invalid order_id format'], 400);
        }

        $meterId = (int) $parts[4];  // Cast the meterId to an integer to match the bigInt type
        Log::info("Extracted Meter ID: " . $meterId);

        // Find the meter record using the meter_id
        $meter = Meter::where('id', $meterId)->first();

        if (!$meter) {
            Log::error('Meter not found with ID: ' . $meterId);
            return response()->json(['status' => 'error', 'message' => 'Meter not found'], 404);
        }

        Log::info('Found Meter: ' . $meterId);

        // Use a database transaction to ensure data consistency
        DB::transaction(function () use ($meter, $transactionStatus) {
            // Update the meter status based on the transaction status
            if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                $meter->update(['status' => 'paid']);
                Log::info('Meter status updated to paid for meter_id: ' . $meter->id);
            } elseif ($transactionStatus === 'expire' || $transactionStatus === 'cancel') {
                $meter->update(['status' => 'unpaid']);
                Log::info('Meter status updated to unpaid for meter_id: ' . $meter->id);
            }
        });

        return response()->json(['status' => 'success']);
    } catch (\Exception $e) {
        Log::error('Error processing webhook: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => 'Error processing webhook'], 500);
    }
}

// Helper function to verify the webhook signature
private function verifyWebhookSignature($rawData, $signatureKey)
{
    $serverKey = env('MIDTRANS_SERVER_KEY');
    $expectedSignature = hash('sha512', $rawData . $serverKey);
    return hash_equals($expectedSignature, $signatureKey);
}
    
    
// ------------------------------------Comment and uncomment---------------------------------------
// ---------------------when connecting to midtrans for saving new url to midtrans------------------
    
    // public function handleWebhook(Request $request)
    // {
    //     Log::info('Webhook hit!');

    //     return response()->json(['status' => '200']);
    // }




    public function handleNotification(Request $request)
    {
        // Handle the notification here
        // For example, you can log or process the request data
        Log::info($request->all());
        
        return response()->json(['status' => 'success']);
    }





    public function fetchMeters($tenant_room_id)
{
    try {
        $meters = Meter::where('tenant_room_id', $tenant_room_id)->get();

        return response()->json([
            'success' => true,
            'meters' => $meters
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

    }
