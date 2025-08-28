<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

Route::post('/qr', function (Request $request) {
    Log::info('QR Code:', ['qr' => $request->input('qrCode')]);
    return response()->json(['message' => 'QR code received']);
});


