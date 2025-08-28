<?php

namespace App\Http\Controllers\simrs\It;

use App\Http\Controllers\Controller;
use App\Services\It\ItService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ItController extends Controller
{

    protected $ItService;
    public function __construct(ItService $itService)
    {
        $this->ItService = $itService;
    }

    public function index()
    {
        return view('SIMRS.It.dashboardIt');
    }

    public function viewLaporan()
    {
        return view('SIMRS.It.laporan');
    }

    public function table()
    {
        $laporan = $this->ItService->getAllLaporan();
        $dataLaporan = [];
    
        foreach ($laporan as $r) {
            $dataLaporan[] = [
                'id' => $r->id,
                'tanggal' => $r->tanggal,
                'waktu_laporan' => $r->waktu_laporan,
                'tanggap_laporan' => $r->tanggap_laporan,
                'nama_barang' => $r->nama_barang,
                'ruangan' => $r->ruangan,
                'analisa' => $r->analisa,
                'tindak_lanjut' => $r->tindak_lanjut,
                'waktu_penyelesaian' => $r->waktu_penyelesaian,
                'created_at' => $r->created_at,
                'updated_at' => $r->updated_at,
            ];
        }
    
        return DataTables::of($dataLaporan)
            ->addIndexColumn()
            ->addColumn('actions', function ($row) {
                return '
                    <button class="btn btn-sm btn-success" onclick="editLaporan(' . $row['id'] . ')">
                        <i class="ri-edit-2-fill"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteLaporan(' . $row['id'] . ')">
                        <i class="ri-delete-bin-fill"></i>
                    </button>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'waktu_laporan' => 'required',
            'tanggap_laporan' => 'nullable|string',
            'nama_barang' => 'required|string|max:255',
            'ruangan' => 'required|string|max:255',
            'analisa' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'waktu_penyelesaian' => 'nullable',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Simpan data ke database
        $this->ItService->createLaporan($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan berhasil dibuat!',
        ], 201);
    }

    public function edit(string $id)
    {
        $laporan = $this->ItService->findLaporan($id);
        return response()->json([
            'status' => 'success',
            'data' => $laporan
        ]);
    }

    public function update(Request $request, string $id)
    {

        $laporan = $this->ItService->findLaporan($id);
        $laporan->update([
                'name' => $request->name,
                'tanggal' => $request->tanggal,
                'waktu_laporan' => $request->waktu_laporan,
                'tanggap_laporan' => $request->tanggap_laporan,
                'nama_barang' => $request->nama_barang,
                'ruangan' => $request->ruangan,
                'analisa' => $request->analisa,
                'tindak_lanjut' => $request->tindak_lanjut,
                'waktu_penyelesaian' => $request->waktu_penyelesaian,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan updated successfully!',
        ], 200);
    }

    public function destroy($id)
    {
        try {
            // Cari role berdasarkan ID
            $laporan = $this->ItService->findLaporan($id);
            // Hapus role
            $laporan->delete();

            // Berikan respons JSON sukses
            return response()->json([
                'success' => true,
                'message' => 'laporan berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function widgetPengaduan(Request $request)
    {
        // Ambil tanggal dari request, jika tidak ada pakai default bulan ini
        $start = $request->start ? Carbon::parse($request->start)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $request->end ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfMonth();

        // Hitung rentang bulan lalu berdasarkan tanggal `start`
        $startOfLastMonth = Carbon::parse($start)->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::parse($start)->subMonth()->endOfMonth();

        // Hitung pengaduan bulan ini (atau sesuai range request)
        $thisMonthCount = $this->ItService->forWidget()
            ->whereBetween('tanggal', [$start, $end])
            ->count();

        // Hitung pengaduan bulan lalu
        $lastMonthCount = $this->ItService->forWidget()
            ->whereBetween('tanggal', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        // Hitung pertumbuhan dalam persen
        if ($lastMonthCount === 0) {
            if ($thisMonthCount === 0) {
                $growthPercent = 0;
            } else {
                $growthPercent = 100;
            }
        } else {
            $growthPercent = (($thisMonthCount - $lastMonthCount) / $lastMonthCount) * 100;
        }

        return response()->json([
            'status' => 'success',
            'totalPengaduan' => $thisMonthCount,
            'lastMonthCount' => $lastMonthCount,   // tambahkan ini
            'growth' => round(abs($growthPercent), 1),
            'growthType' => $growthPercent >= 0 ? 'up' : 'down'
        ]);
    }

    public function widgetAverageResponseTime(Request $request)
    {
        $start = Carbon::parse($request->start)->startOfDay();
        $end = Carbon::parse($request->end)->endOfDay();
    
        $diffInDays = $start->diffInDays($end);
        $lastPeriodStart = $start->copy()->subDays($diffInDays + 1);
        $lastPeriodEnd = $start->copy()->subDay();
    
        // Data periode sekarang
        $laporanThisPeriod = $this->ItService->forWidget()
            ->whereBetween('tanggal', [$start, $end])
            ->whereNotNull('waktu_laporan')
            ->whereNotNull('tanggap_laporan')
            ->get(['tanggal', 'waktu_laporan', 'tanggap_laporan']);
    
        $totalSecondsThisPeriod = 0;
        $countThisPeriod = 0;
    
        foreach ($laporanThisPeriod as $laporan) {
            $waktu = Carbon::parse($laporan->tanggal . ' ' . $laporan->waktu_laporan);
            $tanggap = Carbon::parse($laporan->tanggal . ' ' . $laporan->tanggap_laporan);
            $diffInSeconds = $waktu->diffInSeconds($tanggap, false);
            if ($diffInSeconds >= 0) {
                $totalSecondsThisPeriod += $diffInSeconds;
                $countThisPeriod++;
            }
        }
    
        $avgThisPeriod = $countThisPeriod > 0 ? $totalSecondsThisPeriod / $countThisPeriod : 0;
    
        // Data periode sebelumnya
        $laporanLastPeriod = $this->ItService->forWidget()
            ->whereBetween('tanggal', [$lastPeriodStart, $lastPeriodEnd])
            ->whereNotNull('waktu_laporan')
            ->whereNotNull('tanggap_laporan')
            ->get(['tanggal', 'waktu_laporan', 'tanggap_laporan']);
    
        $totalSecondsLastPeriod = 0;
        $countLastPeriod = 0;
    
        foreach ($laporanLastPeriod as $laporan) {
            $waktu = Carbon::parse($laporan->tanggal . ' ' . $laporan->waktu_laporan);
            $tanggap = Carbon::parse($laporan->tanggal . ' ' . $laporan->tanggap_laporan);
            $diffInSeconds = $waktu->diffInSeconds($tanggap, false);
            if ($diffInSeconds >= 0) {
                $totalSecondsLastPeriod += $diffInSeconds;
                $countLastPeriod++;
            }
        }
    
        $avgLastPeriod = $countLastPeriod > 0 ? $totalSecondsLastPeriod / $countLastPeriod : 0;
    
        // Growth
        $growthPercent = 0;
        if ($avgLastPeriod != 0) {
            $growthPercent = (($avgThisPeriod - $avgLastPeriod) / $avgLastPeriod) * 100;
        }
    
        $growthType = $growthPercent >= 0 ? 'up' : 'down';
    
        return response()->json([
            'status' => 'success',
            'averageResponseTime' => $avgThisPeriod,
            'averageResponseTimeLast' => $avgLastPeriod,
            'growthPercent' => number_format(abs($growthPercent), 2),
            'growthType' => $growthType,
        ]);
    }
    

    public function widgetAverageCompletionTime(Request $request)
    {
        // Ambil tanggal dari request, default ke bulan ini jika tidak ada
        $start = $request->start ? Carbon::parse($request->start)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $request->end ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfMonth();

        // Rentang bulan lalu (berdasarkan tanggal 'start' request)
        $startOfLastMonth = Carbon::parse($start)->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::parse($start)->subMonth()->endOfMonth();

        // Bulan ini (dari request)
        $laporanThisMonth = $this->ItService->forWidget()
            ->whereBetween('tanggal', [$start, $end])
            ->whereNotNull('waktu_laporan')
            ->whereNotNull('waktu_penyelesaian')
            ->get(['waktu_laporan', 'waktu_penyelesaian']);

        $totalSecondsThisMonth = 0;
        $countThisMonth = 0;

        foreach ($laporanThisMonth as $laporan) {
            $startTime = Carbon::parse($laporan->waktu_laporan);
            $endTime = Carbon::parse($laporan->waktu_penyelesaian);
            $diffInSeconds = $startTime->diffInSeconds($endTime, false);
            if ($diffInSeconds >= 0) {
                $totalSecondsThisMonth += $diffInSeconds;
                $countThisMonth++;
            }
        }

        $avgThisMonth = $countThisMonth > 0 ? $totalSecondsThisMonth / $countThisMonth : 0;

        // Bulan lalu
        $laporanLastMonth = $this->ItService->forWidget()
            ->whereBetween('tanggal', [$startOfLastMonth, $endOfLastMonth])
            ->whereNotNull('waktu_laporan')
            ->whereNotNull('waktu_penyelesaian')
            ->get(['waktu_laporan', 'waktu_penyelesaian']);

        $totalSecondsLastMonth = 0;
        $countLastMonth = 0;

        foreach ($laporanLastMonth as $laporan) {
            $startTime = Carbon::parse($laporan->waktu_laporan);
            $endTime = Carbon::parse($laporan->waktu_penyelesaian);
            $diffInSeconds = $startTime->diffInSeconds($endTime, false);
            if ($diffInSeconds >= 0) {
                $totalSecondsLastMonth += $diffInSeconds;
                $countLastMonth++;
            }
        }

        $avgLastMonth = $countLastMonth > 0 ? $totalSecondsLastMonth / $countLastMonth : 0;

        // Hitung pertumbuhan
        $growthPercent = 0;
        if ($avgLastMonth != 0) {
            $growthPercent = (($avgThisMonth - $avgLastMonth) / $avgLastMonth) * 100;
        }

        $growthType = $growthPercent >= 0 ? 'up' : 'down';

        return response()->json([
            'status' => 'success',
            'averageCompletionTime' => $avgThisMonth,
            'averageCompletionTimeLast' => $avgLastMonth,
            'growthPercent' => number_format(abs($growthPercent), 2),
            'growthType' => $growthType,
        ]);
    }

}
