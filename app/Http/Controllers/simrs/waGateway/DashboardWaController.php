<?php

namespace App\Http\Controllers\simrs\waGateway;

use App\Http\Controllers\Controller;
use App\Services\WaGateway\WaGatewayService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardWaController extends Controller
{

    protected $WaGatewayService;
    public function __construct(WaGatewayService $WaGatewayService)
    {
        $this->WaGatewayService = $WaGatewayService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('SIMRS.wa-gateway.dashboardWa');
    }

    public function laporanWa(Request $request){
        return view('SIMRS.wa-gateway.laporanWa');
    }

    public function widgetTerkirim(Request $request)
    {
        // Ambil tanggal dari request, jika tidak ada pakai default bulan ini
        $start = $request->start ? Carbon::parse($request->start)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $request->end ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfMonth();

        // Hitung rentang bulan lalu berdasarkan tanggal `start`
        $startOfLastMonth = Carbon::parse($start)->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::parse($start)->subMonth()->endOfMonth();

        // Hitung wa terkirim bulan ini (atau sesuai range request)
        $thisMonthCount = $this->WaGatewayService->getWaTerkirim()
            ->where('status_send', 'terkirim')
            ->whereBetween('tgl_wa', [$start, $end])
            ->count();

        // Hitung pengaduan bulan lalu
        $lastMonthCount = $this->WaGatewayService->getWaTerkirim()
            ->where('status_send', 'terkirim')
            ->whereBetween('tgl_wa', [$startOfLastMonth, $endOfLastMonth])
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
            'totalTerkirim' => $thisMonthCount,
            'lastMonthCount' => $lastMonthCount,   // tambahkan ini
            'growth' => round(abs($growthPercent), 1),
            'growthType' => $growthPercent >= 0 ? 'up' : 'down'
        ]);
    }

    public function widgetTerjadwal(Request $request)
    {
        // Ambil tanggal dari request, jika tidak ada pakai default bulan ini
        $start = $request->start ? Carbon::parse($request->start)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $request->end ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfMonth();

        // Hitung rentang bulan lalu berdasarkan tanggal `start`
        $startOfLastMonth = Carbon::parse($start)->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::parse($start)->subMonth()->endOfMonth();

        // Hitung wa terkirim bulan ini (atau sesuai range request)
        $thisMonthCount = $this->WaGatewayService->getWaTerkirim()
            ->where('status_send', 'terjadwal')
            ->whereBetween('tgl_wa', [$start, $end])
            ->count();

        // Hitung pengaduan bulan lalu
        $lastMonthCount = $this->WaGatewayService->getWaTerkirim()
            ->where('status_send', 'terjadwal')
            ->whereBetween('tgl_wa', [$startOfLastMonth, $endOfLastMonth])
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
            'totalTerjadwal' => $thisMonthCount,
            'lastMonthCount' => $lastMonthCount,   // tambahkan ini
            'growth' => round(abs($growthPercent), 1),
            'growthType' => $growthPercent >= 0 ? 'up' : 'down',
            'growthPercent' => round($growthPercent, 1),
        ]);
    }

    public function widgetGagal(Request $request)
    {
        // Ambil tanggal dari request, jika tidak ada pakai default bulan ini
        $start = $request->start ? Carbon::parse($request->start)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $request->end ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfMonth();

        // Hitung rentang bulan lalu berdasarkan tanggal `start`
        $startOfLastMonth = Carbon::parse($start)->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::parse($start)->subMonth()->endOfMonth();

        // Hitung wa terkirim bulan ini (atau sesuai range request)
        $thisMonthCount = $this->WaGatewayService->getWaTerkirim()
            ->where('status_send', 'gagal')
            ->whereBetween('tgl_wa', [$start, $end])
            ->count();

        // Hitung pengaduan bulan lalu
        $lastMonthCount = $this->WaGatewayService->getWaTerkirim()
            ->where('status_send', 'gagal')
            ->whereBetween('tgl_wa', [$startOfLastMonth, $endOfLastMonth])
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
            'totalGagal' => $thisMonthCount,
            'lastMonthCount' => $lastMonthCount,   // tambahkan ini
            'growth' => round(abs($growthPercent), 1),
            'growthType' => $growthPercent >= 0 ? 'up' : 'down'
        ]);
    }

    public function widgetBelum(Request $request)
    {
        // Ambil tanggal dari request, jika tidak ada pakai default bulan ini
        $start = $request->start ? Carbon::parse($request->start)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $request->end ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfMonth();

        // Hitung rentang bulan lalu berdasarkan tanggal `start`
        $startOfLastMonth = Carbon::parse($start)->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::parse($start)->subMonth()->endOfMonth();

        // Hitung wa terkirim bulan ini (atau sesuai range request)
        $thisMonthCount = $this->WaGatewayService->getWaTerkirim()
            ->where('status_send', 'belum')
            ->whereBetween('tgl_wa', [$start, $end])
            ->count();

        // Hitung pengaduan bulan lalu
        $lastMonthCount = $this->WaGatewayService->getWaTerkirim()
            ->where('status_send', 'belum')
            ->whereBetween('tgl_wa', [$startOfLastMonth, $endOfLastMonth])
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
            'totalBelum' => $thisMonthCount,
            'lastMonthCount' => $lastMonthCount,   // tambahkan ini
            'growth' => round(abs($growthPercent), 1),
            'growthType' => $growthPercent >= 0 ? 'up' : 'down'
        ]);
    }

    public function widgetBatal(Request $request)
    {
        // Ambil tanggal dari request, jika tidak ada pakai default bulan ini
        $start = $request->start ? Carbon::parse($request->start)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $request->end ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfMonth();

        // Hitung rentang bulan lalu berdasarkan tanggal `start`
        $startOfLastMonth = Carbon::parse($start)->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::parse($start)->subMonth()->endOfMonth();

        // Hitung wa terkirim bulan ini (atau sesuai range request)
        $thisMonthCount = $this->WaGatewayService->getWaTerkirim()
            ->where('status_send', 'batal')
            ->whereBetween('tgl_wa', [$start, $end])
            ->count();

        // Hitung pengaduan bulan lalu
        $lastMonthCount = $this->WaGatewayService->getWaTerkirim()
            ->where('status_send', 'batal')
            ->whereBetween('tgl_wa', [$startOfLastMonth, $endOfLastMonth])
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
            'totalBatal' => $thisMonthCount,
            'lastMonthCount' => $lastMonthCount,   // tambahkan ini
            'growth' => round(abs($growthPercent), 1),
            'growthType' => $growthPercent >= 0 ? 'up' : 'down'
        ]);
    }

    public function tabelData(Request $request)
    {
        $start = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

        Log::info("Start: $start, End: $end");

        $data = $this->WaGatewayService->getWaTerkirim()
        ->select([
            'id',
            'no_rawat',
            'no_rm',
            'nama',
            'tgl_wa',
            'no_telp',
            'wa_status',
            'status_send',
        ])
        ->whereBetween('tgl_wa', [$start, $end])
        ->orderBy('tgl_wa', 'desc');

        // Beri index manual jika perlu
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('actions', function ($row) {
                return '<button class="btn btn-sm btn-primary" onclick="detailLog(' . $row['id'] . ')">Detail</button>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function detailLog($id)
    {
        $data = $this->WaGatewayService->LogWa($id);
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
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
