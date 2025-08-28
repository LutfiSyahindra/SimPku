<?php

namespace App\Http\Controllers\simrs\Surat;

use App\Http\Controllers\Controller;
use App\Services\Surat\SuratMasukService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SuratMasukController extends Controller
{
    protected $SuratMasukService;
    public function __construct(SuratMasukService $SuratMasukService)
    {
        $this->SuratMasukService = $SuratMasukService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('SIMRS.Surat.suratMasuk.SuratMasuk');
    }

    public function table()
    {
        $SuratMasuk = $this->SuratMasukService->getSuratMasuk();
        $dataSuratMasuk = [];
        foreach ($SuratMasuk as $r) {
            $dataSuratMasuk[] = [
                'id' => $r['id'],
                'no_surat' => $r['no_surat'],
                'pengirim' => $r['pengirim'],
                'perihal' => $r['perihal'],
                'tgl_surat' => $r['tgl_surat'],
                'tgl_terima' => $r['tgl_terima'],
                'created_at' => $r['created_at'],
                'updated_at' => $r['updated_at'],
            ];
        }

        return DataTables::of($dataSuratMasuk)
        ->addIndexColumn()
        ->addColumn('actions', function ($dataSuratMasuk) {
            return '
                <button class="btn btn-sm btn-success" onclick="editSuratMasuk(' . $dataSuratMasuk['id'] . ')"> <i class=" ri-edit-2-fill "></i></button> 
                <button class="btn btn-sm btn-danger" onclick="deleteSuratMasuk(' . $dataSuratMasuk['id'] . ')">  <i class=" ri-delete-bin-fill"></i></button>
                <button class="btn btn-sm btn-warning" onclick="viewSuratMasuk(' . $dataSuratMasuk['id'] . ')">  <i class="ri-eye-fill"></i></button>
            ';
        })
        ->rawColumns(['actions'])
        ->make(true);

    }

    public function store(Request $request)
    {
        // Validasi request
        $validated = $request->validate([
            'NoSurat'           => 'required|string|max:100',
            'TglSurat'          => 'required|date',
            'TglDiterima'       => 'required|date',
            'instansiPengirim'  => 'required|string|max:255',
            'NoAgenda'          => 'required|string|max:50|unique:surat_masuk,no_agenda',
            'klasifikasi'       => 'required|string|max:100',
            'Perihal'           => 'required|string|max:100',
            'Lampiran'          => 'required|integer|min:0',
            'Status'            => 'required|string|max:50',
            'Sifat'             => 'required|string|max:50',
            'fileSurat'         => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB
        ]);

        try {
            $file = $request->file('fileSurat');

            // Simpan file di storage/app/public/surat_masuk
            $filePath = $file->store('public/surat_masuk'); 
            $fileName = $file->getClientOriginalName();
            $fileMime = $file->getClientMimeType();

            // Simpan data ke DB lewat service
            $id = $this->SuratMasukService->storeSuratMasuk([
                'no_surat'     => $validated['NoSurat'],
                'tgl_surat'    => $validated['TglSurat'],
                'tgl_terima'   => $validated['TglDiterima'],
                'pengirim'     => $validated['instansiPengirim'],
                'no_agenda'    => $validated['NoAgenda'],
                'klasifikasi'  => $validated['klasifikasi'],
                'perihal'      => $validated['Perihal'],
                'lampiran'     => $validated['Lampiran'],
                'status'       => $validated['Status'],
                'sifat'        => $validated['Sifat'],
                'file'    => $filePath,   // Simpan path file
                'file_name'    => $fileName,   // Nama file asli
                'file_mime'    => $fileMime,
                'created_by'   => Auth::id()
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Surat Masuk berhasil disimpan!',
                'id'      => $id,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan surat masuk: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request' => $request->all()
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan surat masuk. Silakan coba lagi.',
            ], 500);
        }
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
        $SuratMasuk = $this->SuratMasukService->findSuratMasuk($id);
        return response()->json($SuratMasuk);
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
