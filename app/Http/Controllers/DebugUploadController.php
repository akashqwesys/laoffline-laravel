<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebugUploadController extends Controller
{
    public function testUpload(Request $request)
    {
        $startTime = microtime(true);

        if (!$request->hasFile('debug_file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file = $request->file('debug_file');
        $size = $file->getSize(); // in bytes

        $responseData = [
            'filename' => $file->getClientOriginalName(),
            'size_kb' => round($size / 1024, 2),
            'size' => $size,
            'mime' => $file->getMimeType(),
            'total_time_seconds' => round(microtime(true) - $startTime, 4)
        ];

        // Log details to storage/logs/laravel.log
        Log::info("DEBUG UPLOAD ATTEMPT", $responseData);

        return response()->json([
            'status' => 'success',
            'data' => $responseData,
            'message' => 'Upload processed by Laravel'
        ]);
    }
}
