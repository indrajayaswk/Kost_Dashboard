<?php

// app/Http/Controllers/BotController.php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Tenant;

class BotController extends Controller
{
    public function checkTenant(Request $request)
    {
        $tenant = Tenant::where('phone', $request->phone)->first();
        // Validate incoming data (phone number)
        Log::info('Received request data:', $request->all());
        $request->validate([
            'phone' => 'required|exists:tenants',  // ini ngecek data 'phone' ada atau tidak dari tabel 'penghunis'
        ]);
        



        if ($tenant) {
            return response()->json(['status' => 'found', 'tenant' => $tenant]);
        } else {
            return response()->json(['status' => 'not_found']);
        }
    }
}

