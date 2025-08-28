<?php

namespace App\Http\Controllers\simrs\display;

use App\Http\Controllers\Controller;
use App\Services\AntriKasirKhanza\AntriKasirKhanzaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KasirKhnzaController extends Controller
{

    protected $AntriKasirKhanzaService;
    public function __construct(AntriKasirKhanzaService $AntriKasirKhanzaService)
    {
        $this->AntriKasirKhanzaService = $AntriKasirKhanzaService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('SIMRS.display.displayKasirKhanza');
    }

    public function panggilAntrean(){
        $data = $this->AntriKasirKhanzaService->getAntriKasir();
        Log::info($data);
        return response()->json($data);
    }

    public function updateAntrean(Request $request){
        $panggil = 'Dipanggil';
        $this->AntriKasirKhanzaService->updateAntriKasir($panggil);
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
