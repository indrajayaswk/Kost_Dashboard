<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;

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
    public function index()
    {
        $tenantRooms = \App\Models\TenantRoom::with('room', 'primaryTenant')->get();
    
        return view('admin2.midtrans.index', [
            'tenantRooms' => $tenantRooms,
        ]);
    }

    // Show meter data related to the selected tenant room (existing)
    public function showMeter(Request $request)
    {
        try {
            $tenantRoom = \App\Models\TenantRoom::with('primaryTenant', 'room')->findOrFail($request->tenant_room_id);
    
            // Fetch the meters related to the selected tenant room
            $meters = \App\Models\Meter::where('tenant_room_id', $request->tenant_room_id)->get();
    
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
            $data = $request->validate([
                'tenant_room_id' => 'required|exists:tenant_rooms,id',
                'meter_id' => 'required|exists:meters,id',
            ]);
    
            // Find the tenant room and meter data
            $tenantRoom = \App\Models\TenantRoom::with(['primaryTenant', 'room'])->findOrFail($data['tenant_room_id']);
            $meter = \App\Models\Meter::findOrFail($data['meter_id']);
    
            // Extract tenant and room data
            $tenant = $tenantRoom->primaryTenant;
            $room = $tenantRoom->room;
    
            // Prepare the payment parameters
            $params = [
                'transaction_details' => [
                    'order_id' => 'ORDER-' . $room->room_number . '-' . $meter->month . '-' . $meter->id,
                    'gross_amount' => $room->room_price + $meter->total_price,
                ],
                'customer_details' => [
                    'first_name' => $tenant->name,
                    'email' => 'akunawur2@gmail.com',
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
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $paymentLink = 'https://app.sandbox.midtrans.com/snap/v4/redirection/' . $snapToken;
    
            // Update the meter's pay_proof and status
            $meter->update([
                'pay_proof' => $paymentLink,
                'status' => 'unpaid',
            ]);
    
            return view('admin2.midtrans.components.midtrans-add', [
                'tenantRoom' => $tenantRoom,
                'meters' => \App\Models\Meter::where('tenant_room_id', $tenantRoom->id)->get(),
                'snapToken' => $snapToken,
                'paymentLink' => $paymentLink,
                'debugData' => [
                    'params' => $params,
                    'snapToken' => $snapToken,
                    'paymentLink' => $paymentLink,
                ],
            ]);
            
        } catch (\Exception $e) {
            return view('admin2.midtrans.index', [
                'tenantRooms' => \App\Models\TenantRoom::with('room', 'primaryTenant')->get(),
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    public function handleWebhook(Request $request)
    {
        Log::info('Webhook Received:');
        
        // Log the raw payload for debugging
        $rawData = file_get_contents('php://input');
        Log::info('Raw Data:', ['raw_data' => $rawData]);
    
        // Process the webhook notification using the Midtrans Notification class
        try {
            $notification = new \Midtrans\Notification();
            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
    
            Log::info('Transaction Status: ' . $transactionStatus);
            Log::info('Order ID: ' . $orderId);
    
            // Extract meter ID from the order_id format: ORDER-room_number-month-meter_id
            $parts = explode('-', $orderId);
    
            if (count($parts) < 4) {
                Log::error('Invalid order_id format: ' . $orderId);
                return response()->json(['status' => 'error', 'message' => 'Invalid order_id format'], 400);
            }
            ///the seperation of the meter ID is goten from data in transaction_id! it is decripted and got it
            $roomNumber = $parts[1];
            $year=$parts[2];
            $month = $parts[3];
            $day=$parts[4];
            $meterId = (int) $parts[5];  // Cast the meterId to an integer to match the bigInt type
    
            Log::info("Extracted Meter ID: " . $meterId);
    
            // Find the meter record using the meter_id explicitly using where
            $meter = \App\Models\Meter::where('id', $meterId)->first();
            
            if (!$meter) {
                Log::error('Meter not found with ID: ' . $meterId);
                return response()->json(['status' => 'error', 'message' => 'Meter not found'], 404);
            }
    
            Log::info('Found Meter: ' . $meterId);
            
            // Update the meter status based on the transaction status
            if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                $meter->update(['status' => 'paid']);
                Log::info('Meter status updated to paid for meter_id: ' . $meterId);
            } elseif ($transactionStatus === 'expire' || $transactionStatus === 'cancel') {
                $meter->update(['status' => 'unpaid']);
                Log::info('Meter status updated to unpaid for meter_id: ' . $meterId);
            }
    
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error processing webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error processing webhook'], 500);
        }
    }
    
    

//     public function handleWebhook(Request $request)
// {
//     Log::info('Webhook hit!');

//     return response()->json(['status' => 'Webhook received']);
// }
public function handleNotification(Request $request)
{
    // Handle the notification here
    // For example, you can log or process the request data
    Log::info($request->all());
    
    return response()->json(['status' => 'success']);
}
}
