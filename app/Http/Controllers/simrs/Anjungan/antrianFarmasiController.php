<?php

namespace App\Http\Controllers\simrs\Anjungan;

use App\Http\Controllers\Controller;
use App\Services\Anjungan\antrianFarmasiService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class antrianFarmasiController extends Controller
{

    protected $antrianFarmasiService;
    public function __construct(antrianFarmasiService $antrianFarmasiService)
    {
        $this->antrianFarmasiService = $antrianFarmasiService;
    }
    /**
     * Display a listing of the resource.
     */
    public function generateAntrian(Request $request)
    {
        try {
            $no_rawat = $request->no_rawat;

            // Hapus semua jenis spasi (termasuk spasi tersembunyi)
            $no_rawat = preg_replace('/\s+/', '', $no_rawat);

            Log::info("No Rawat setelah preg_replace: '" . $no_rawat . "'");

            $data = $this->antrianFarmasiService->generateAntrianFarmasi($no_rawat);

            return response()->json([
                'status' => 'success',
                'nomor_antrian' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil nomor antrian',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function cetakAntrian($no_resep)
    {
        // Ambil data berdasarkan no_resep dan konversi ke array
        $dataAntrian = $this->antrianFarmasiService->generateDataFarmasi($no_resep)->toArray();
        

        // Pastikan data tidak kosong dan ambil elemen pertama
        if (empty($dataAntrian)) {
            return abort(404, "Data tidak ditemukan");
        }
        $dataAntrian = $dataAntrian[0]; // Ambil elemen pertama dari array

        $pdf = Pdf::loadView('SIMRS.anjungan.antrianFarmasi.pdfAntrianAnjunganFarmasi', [
            'no_resep' => $dataAntrian['no_resep'],
            'nomor' => $dataAntrian['no_resep_potong'],
            'tgl_perawatan' => $dataAntrian['tgl_perawatan'] ?? 'Tidak Diketahui',
            'tgl_peresepan' => $dataAntrian['tgl_peresepan'] ?? 'Tidak Diketahui',
            'jam_peresepan' => $dataAntrian['jam_peresepan'] ?? 'Tidak Diketahui',
            'jam_penyerahan' => $dataAntrian['jam_penyerahan'] ?? 'Tidak Diketahui',
            'obat' => json_decode($dataAntrian['detail_obat'] ?? '[]', true) // Jika detail_obat berbentuk JSON
        ])->setPaper([0, 0, 226.77, 1000], 'portrait');

        return $pdf->stream("Antrian_{$dataAntrian['no_resep']}.pdf");
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
