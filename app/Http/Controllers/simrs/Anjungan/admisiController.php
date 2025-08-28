<?php

namespace App\Http\Controllers\simrs\Anjungan;

use App\Http\Controllers\Controller;
use App\Services\Anjungan\admisiService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class admisiController extends Controller
{

    protected $admisiService;
    public function __construct(admisiService $admisiService)
    {
        $this->admisiService = $admisiService;
    }
    /**
     * Display a listing of the resource.
     */
    public function generateAntrianAdmisi()
    {
        try {
            $nomorAntrian = $this->admisiService->generateNomorAntrian();

            return response()->json([
                'status' => 'success',
                'nomor_antrian' => $nomorAntrian
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil nomor antrian',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function cetakAntrian($nomor)
    {
        $customPaper = array(0, 0, 226, 400); // 80mm x 150mm

        $pdf = Pdf::loadView('SIMRS.anjungan.admisi.pdfAntrianAnjunganAdmisi', ['nomor' => $nomor])
            ->setPaper($customPaper, 'portrait'); // Ukuran thermal printer

        return $pdf->stream("Antrian_$nomor.pdf");
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
