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

    // Display the Midtrans payment page
    public function index()
    {
        return view('admin2.midtrans.index');
    }

    // Create Payment URL for Midtrans with hardcoded data
    public function createPayment(Request $request)
    {
        // Validate the input data
        $data = $request->validate([
            'gross_amount' => 'required|numeric|min:1',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
        ]);
    
        // Ensure start_time is set to 1 month in the future, correctly formatted
        $start_time = now()->addMonth()->format('Y-m-d H:i:s') . ' +0700'; // Add 1 month to current time
        
        // Set transaction parameters
        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . uniqid(),
                'gross_amount' => $data['gross_amount'],
            ],
            'customer_details' => [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
            ],
            'enabled_payments' => ["bank_transfer", "gopay", "credit_card", "BCA"],
            'expiry' => [
                'start_time' => $start_time,  // Set to 1 month in the future
                'unit' => 'minutes',
                'duration' => 60,
            ],
        ];
    
        Log::info('Midtrans Payment Params:', ['params' => $params]);
    
        try {
            // Generate snap token
            $snapToken = Snap::getSnapToken($params);
            Log::info('Midtrans Snap Token Generated:', ['snap_token' => $snapToken]);
    
            return view('admin2.midtrans.index', [
                'status_code' => 200, // HTTP 200 OK
                'message' => 'Snap token generated successfully.',
                'snapToken' => $snapToken,
                'params' => $params,
                'paymentLink' => 'https://app.sandbox.midtrans.com/snap/v4/redirection/' . $snapToken,
            ]);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            Log::error('Midtrans Payment Error:', [
                'error' => $errorMessage,
                'status_code' => 500, // Internal Server Error
            ]);
    
            return view('admin2.midtrans.index', [
                'status_code' => 500,
                'message' => 'An error occurred while generating the snap token.',
                'error' => $errorMessage,
            ]);
        }
    }
    
    

}


