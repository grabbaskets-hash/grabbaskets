<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

// Add a route to serve images directly from storage
Route::get('/serve-image/{type}/{filename}', function ($type, $filename) {
    // Validate type (only allow 'products' for security)
    if ($type !== 'products') {
        abort(404);
    }
    
    $path = $type . '/' . $filename;
    
    // Check if file exists
    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }
    
    // Get file contents and mime type
    $file = Storage::disk('public')->get($path);
    $fullPath = Storage::disk('public')->path($path);
    
    // Detect mime type
    $mimeType = 'image/jpeg'; // default
    if (function_exists('mime_content_type')) {
        $detectedType = mime_content_type($fullPath);
        if ($detectedType) {
            $mimeType = $detectedType;
        }
    }
    
    return Response::make($file, 200, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=86400', // Cache for 1 day
    ]);
})->name('serve.image');