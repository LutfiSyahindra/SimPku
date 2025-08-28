<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class QrCodeController extends Controller
{

    public function index()
    {
        return view('SIMRS.wa-gateway.waGateway');
    }

    public function store(Request $request)
    {
        $qrCode = $request->input('qrCode');

        // Simpan QR ke file storage
        Storage::disk('local')->put('qr.txt', $qrCode);

        return response()->json(['message' => 'QR code disimpan']);
    }

    public function show()
    {
        $qrCodeString = Storage::get('qr.txt'); // Ambil QR dari file

        return view('qrcode', ['qrCodeString' => $qrCodeString]);
    }

}
