<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class KTPImageController extends Controller
{
    // Ensure only authenticated users can access the image
    public function __construct()
    {
        $this->middleware('auth'); // This ensures only authenticated users can access the route
    }

    public function show($filename)
    {
        // Define the path to the image in storage
        $filePath = storage_path('app/private/ktp_images/' . $filename);

        // Check if the file exists
        if (file_exists($filePath)) {
            // Serve the file securely if the file exists
            return response()->file($filePath);
        }

        // If the file doesn't exist, return a 404 error
        return abort(404, 'File not found');
    }
}




