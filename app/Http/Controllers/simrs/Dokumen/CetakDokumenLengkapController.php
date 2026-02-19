<?php

namespace App\Http\Controllers\simrs\Dokumen;

use App\Http\Controllers\Controller;
use App\Services\Dokumen\CetakDokumenLengkapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CetakDokumenLengkapController extends Controller
{
    protected $service;

    public function __construct(CetakDokumenLengkapService $service)
    {
        $this->service = $service;
    }

    public function cetak(Request $request)
    {
        $no_rawat = urldecode($request->get('no_rawat'));
        return $this->service->generateCpptPdf($no_rawat);
        // try {
        //     return $this->service->generateCpptPdf($no_rawat);
        // } catch (\Exception $e) {
        //     abort(404, $e->getMessage());
        // }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
