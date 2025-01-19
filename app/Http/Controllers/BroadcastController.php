<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BroadcastController extends Controller
{
    /**
     * Display the broadcast page with a list of tenants.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve all tenants
        $tenants = Tenant::all();

        // Log the retrieved tenants for debugging
        Log::info('Retrieved tenants for broadcasting.', [
            'total_tenants' => $tenants->count()
        ]);

        return view('admin.broadcast.index', compact('tenants'));
    }

    /**
     * Send broadcast messages to selected tenants.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        // Validate input data
        $validatedData = $request->validate([
            'tenant_ids' => 'required|array|min:1', // Ensure at least one tenant is selected
            'tenant_ids.*' => 'exists:tenants,id', // Ensure tenant_ids exists in the tenants table
            'message' => 'required|string',
        ]);

        // Log the incoming request data
        Log::info('Broadcast send request received.', $validatedData);

        // Collect recipients
        $recipients = [];
        foreach ($request->tenant_ids as $tenantId) {
            $tenant = Tenant::find($tenantId);

            if ($tenant) {
                $tenantPhone = preg_replace('/\D/', '', $tenant->phone);

                // Ensure the phone number starts with the correct country code
                if (substr($tenantPhone, 0, 2) !== '62') {
                    $tenantPhone = '62' . substr($tenantPhone, 1);
                }

                $recipients[] = $tenantPhone;
            } else {
                Log::warning('Tenant not found during broadcast.', ['tenant_id' => $tenantId]);
            }
        }

        // If no valid recipients, return an error
        if (empty($recipients)) {
            return redirect()->back()->with('error', 'No valid tenants selected.');
        }

        // Prepare the payload for the bot server
        $payload = [
            'message' => $request->message,
            'recipients' => $recipients,
        ];

        // Log the payload for debugging
        Log::info('Sending broadcast request.', $payload);

        // Send the broadcast request to the bot server
        try {
            $response = Http::post('http://localhost:3000/broadcast', $payload);

            if (!$response->successful()) {
                Log::error('Failed to send broadcast request.', [
                    'response_status' => $response->status(),
                    'response_body' => $response->body(),
                ]);

                return redirect()->back()->with('error', 'Failed to send broadcast. Please check the logs for more details.');
            }
        } catch (\Exception $e) {
            Log::error('Exception while sending broadcast.', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'An error occurred while sending the broadcast. Please check the logs for more details.');
        }

        Log::info('Broadcast request sent successfully.');

        return redirect()->back()->with('success', 'Broadcast message sent successfully!');
    }
}
