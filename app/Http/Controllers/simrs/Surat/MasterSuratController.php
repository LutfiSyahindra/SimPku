<?php

namespace App\Http\Controllers\simrs\Surat;

use App\Http\Controllers\Controller;
use App\Services\Surat\KategoriSurat\KategoriSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class MasterSuratController extends Controller
{
    protected $KategoriSuratService;
    public function __construct(KategoriSuratService $KategoriSuratService)
    {
        $this->KategoriSuratService = $KategoriSuratService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('SIMRS.Surat.master.masterSurat');
    }

    public function table()
    {
        $KatgeoriTable = $this->KategoriSuratService->getKategoriSurat();
        $dataKategori = [];
        foreach ($KatgeoriTable as $r) {
            $dataKategori[] = [
                'id' => $r['id'],
                'nama' => $r['nama'],
                'deskripsi' => $r['deskripsi'],
                'created_at' => $r['created_at'],
                'updated_at' => $r['updated_at'],
            ];
        }

        return DataTables::of($dataKategori)
        ->addIndexColumn()
        ->addColumn('actions', function ($dataKategori) {
            return '
                <button class="btn btn-sm btn-success" onclick="editKategori(' . $dataKategori['id'] . ')"> <i class=" ri-edit-2-fill "></i></button> 
                <button class="btn btn-sm btn-danger" onclick="deleteKategori(' . $dataKategori['id'] . ')">  <i class=" ri-delete-bin-fill"></i></button>
            ';
        })
        ->rawColumns(['actions'])
        ->make(true);

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
        $validator = $request->validate([
            'KategoriSurat' => 'required',     
            'Deskripsi' => 'required',
        ]);

        $this->KategoriSuratService->storeKategoriSurat([
            'nama' => $validator['KategoriSurat'],
            'deskripsi' => $validator['Deskripsi'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori Surat Created successful!',
        ], 201);
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
        $kategoriSurat = $this->KategoriSuratService->findKategoriSurat($id);
        return response()->json($kategoriSurat);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Log::info($request->all());

        $dataKategoriSurat = $this->KategoriSuratService->findKategoriSurat($id);
        $dataKategoriSurat->update(['nama' => $request->KategoriSurat, 'deskripsi' => $request->Deskripsi]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori Surat Updated successful!',  
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Cari role berdasarkan ID
            $KategoriSurat = $this->KategoriSuratService->findKategoriSurat($id);
            // Hapus role
            $KategoriSurat->delete();

            // Berikan respons JSON sukses
            return response()->json([
                'success' => true,
                'message' => 'Kategori Surat berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
