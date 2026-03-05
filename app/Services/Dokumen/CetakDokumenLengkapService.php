<?php

namespace App\Services\Dokumen;

use App\Repositories\Dokumen\CetakDokumenLengkapRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class CetakDokumenLengkapService
{
    protected $repo;

    public function __construct(CetakDokumenLengkapRepository $repo)
    {
        $this->repo = $repo;
    }

    public function generateCpptPdf(string $no_rawat)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Ambil Data CPPT
        |--------------------------------------------------------------------------
        */
        $cppt = $this->repo->getDokumenCppt($no_rawat);
        // Log::info(['CPPT Data:', $cppt]);

        if ($cppt->isEmpty()) {
            throw new \Exception('Data CPPT tidak ditemukan');
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Ambil Header Lab Terakhir (jika ada)
        |--------------------------------------------------------------------------
        */
        $headerLab = $this->repo->getHeaderLab($no_rawat);

        $detailLab = collect();

        if ($headerLab) {
            $detailLab = $this->repo->getDetailLab( 
                $headerLab->no_rawat,
                $headerLab->tgl_periksa,
                $headerLab->jam);
            }

        $resume = $this->repo->getResumeByNoRawat($no_rawat);

        $spri = $this->repo->getSpriByNoRawat($no_rawat);

        $ritp = $this->repo->getRitpByNoRawat($no_rawat);

        Log::info(['SPRI Data:', $spri]);

        /*
        |--------------------------------------------------------------------------
        | 3. Setting RS
        |--------------------------------------------------------------------------
        */
        $setting = $this->repo->getSetting();

        /*
        |--------------------------------------------------------------------------
        | 4. Amankan nama file (hindari slash)
        |--------------------------------------------------------------------------
        */
        $safeNoRawat = str_replace(['/', '\\'], '-', $no_rawat);

        /*
        |--------------------------------------------------------------------------
        | 5. Generate PDF
        |--------------------------------------------------------------------------
        */
        return Pdf::loadView('SIMRS.Dokumen.cetakLengkap', [
                'cppt'       => $cppt,
                'headerLab'  => $headerLab,
                'detailLab'  => $detailLab,
                'resume'     => $resume,
                'spri'       => $spri,
                'ritp'       => $ritp,
                'setting'    => $setting
            ])
            ->setPaper('A4', 'portrait')
            ->stream("DokumenLengkap-{$safeNoRawat}.pdf");
    }

    public function generateResumePdf(string $no_rawat){
        $resume = $this->repo->getResumeByNoRawat($no_rawat);

        if (!$resume) {
            throw new \Exception('Data Resume tidak ditemukan');
        }

        $setting = $this->repo->getSetting();
        $safeNoRawat = str_replace(['/', '\\'], '-', $no_rawat);

        return Pdf::loadView('SIMRS.Dokumen.cetakResume', [
                'resume' => $resume,
                'setting' => $setting
            ])
            ->setPaper('A4', 'portrait')
            ->stream("Resume-{$safeNoRawat}.pdf");
    }


    // public function generateCpptPdf(string $no_rawat)
    // {
    //     $data = $this->repo->getDokumenCppt($no_rawat);
    //     Log::info($data);

    //     if ($data->isEmpty()) {
    //         throw new \Exception('Data CPPT tidak ditemukan');
    //     }

    //     $setting = $this->repo->getSetting();
    //     $safeNoRawat = str_replace(['/', '\\'], '-', $no_rawat);

    //     return Pdf::loadView('SIMRS.Dokumen.cetakLengkap', [
    //             'data' => $data,
    //             'setting' => $setting
    //         ])
    //         ->setPaper('A4', 'portrait')
    //         ->stream("CPPT-{$safeNoRawat}.pdf");
    // }
}
