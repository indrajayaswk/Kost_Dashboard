<?php

// app/Http/Controllers/BotController.php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Penghuni; 

class BotController extends Controller
{
    public function checkTenant(Request $request)
    {
        $tenant = Penghuni::where('telphon', $request->telphon)->first();
        // Validate incoming data (phone number)
        Log::info('Received request data:', $request->all());
        $request->validate([
            'telphon' => 'required|exists:penghunis',  // ini ngecek data 'telphon' ada atau tidak dari tabel 'penghunis'
        ]);
        



        if ($tenant) {
            return response()->json(['status' => 'found', 'tenant' => $tenant]);
        } else {
            return response()->json(['status' => 'not_found']);
        }
    }
}

