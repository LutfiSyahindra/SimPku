<?php

namespace App\Http\Controllers\simrs\taskId;

use App\Http\Controllers\Controller;
use App\Services\TaskId\TaskIdService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class taskIdController extends Controller
{
    protected $TaskIdService;
    public function __construct(TaskIdService $TaskIdService)
    {
        $this->TaskIdService = $TaskIdService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('SIMRS.taskId.taskId');
    }

    public function getTaskId(Request $request)
    {
        $startReg = $request->start;
        $endReg   = $request->end;

         // Normalisasi format dari 2025-08-14 -> 2025/08/14
        $start = str_replace('-', '/', $request->start);
        $end   = str_replace('-', '/', $request->end);

        // Log::info("Start: $start, End: $end, StartReg: $startReg, EndReg: $endReg");

        $count = $this->TaskIdService->getTaskId()
            ->select(DB::raw("LEFT(no_rawat, 10) as tanggal"), 'no_rawat')
            ->whereBetween(DB::raw("LEFT(no_rawat, 10)"), [$start, $end])
            ->where ('status_daftar', 'Onsite')
            ->groupBy('tanggal', 'no_rawat') // grup berdasarkan tanggal + no_rawat
            ->get()
            ->count();
        
        // Belum Terkirim   
        $belumTerkirim = DB::connection('mysql_khanza')
        ->table('reg_periksa as rp')
        ->select('rp.no_rawat')
        ->whereBetween(DB::raw("rp.tgl_registrasi"), [$startReg, $endReg])
        ->whereNotIn('rp.no_rawat', function($query) {
            $query->select('no_rawat')
                ->from('log_taskid');
        })
        ->whereNotIn('rp.no_rawat', function($query) {
            $query->select('no_rawat')
                    ->from('referensi_mobilejkn_bpjs');
        })
        ->distinct()
        ->count();

        // TaskId JKN
        $JKNcount = $this->TaskIdService->getTaskId()
            ->select(DB::raw("LEFT(no_rawat, 10) as tanggal"), 'no_rawat')
            ->whereBetween(DB::raw("LEFT(no_rawat, 10)"), [$start, $end])
            ->where ('status_daftar', 'MJKN')
            ->groupBy('tanggal', 'no_rawat') // grup berdasarkan tanggal + no_rawat
            ->get()
            ->count();
        
        //Jkn Belum
        $JKNbelumTerkirim = DB::connection('mysql_khanza')
            ->table('reg_periksa as rp')
            ->join('referensi_mobilejkn_bpjs as jkn', 'rp.no_rawat', '=', 'jkn.no_rawat')
            ->whereBetween('rp.tgl_registrasi', [$startReg, $endReg])
            ->where('jkn.status', '!=', 'batal')
            ->whereNotIn('rp.no_rawat', function($query) {
                $query->select('no_rawat')->from('log_taskid');
            })
            ->distinct()
            ->count();


        // Hitung total rata-rata waktu per no_rawat (taskid terkecil > 0 → taskid terbesar > 0)
        $avgMinutes = DB::connection('mysql_khanza')
            ->table('referensi_mobilejkn_bpjs_taskid')
            ->select(
                'no_rawat',
                DB::raw("
                    TIMESTAMPDIFF(
                        MINUTE,
                        MIN(CASE WHEN taskid > '0' THEN waktu END),
                        MAX(CASE WHEN taskid > '0' THEN waktu END)
                    ) AS total_menit
                ")
            )
            ->whereBetween(DB::raw('DATE(waktu)'), [$startReg, $endReg])
            ->groupBy('no_rawat')
            ->get();

        // Log::info($avgMinutes);

        $totalRata = $avgMinutes->sum('total_menit') / $avgMinutes->count();
        $totalSeconds = $totalRata * 60;

        // ubah ke format HH:MM:SS
        $formattedTime = gmdate("H:i:s", $totalSeconds);

        return response()->json([
            'count' => $count,
            'belumTerkirim' => $belumTerkirim,
            'JKNcount' => $JKNcount,
            'JKNbelumTerkirim' => $JKNbelumTerkirim,
            'avgMinutes' => $avgMinutes,
            'totalRata' => $formattedTime,
            'status' => 'success'
        ]);
    }

    public function rataAdmisi(Request $request)
    {
        $startReg = $request->start;
        $endReg   = $request->end;

        // Waktu Tunggu Admisi
        $admisi = DB::connection('mysql_khanza')
        ->table('referensi_mobilejkn_bpjs_taskid')
        ->select(
            'no_rawat',
            DB::raw("
                TIMESTAMPDIFF(
                    MINUTE,
                    MIN(CASE WHEN taskid = '1' THEN waktu END),
                    MAX(CASE WHEN taskid = '2' THEN waktu END)
                ) AS total_menit
            ")
        )
        ->whereBetween(DB::raw('DATE(waktu)'), [$startReg, $endReg])
        ->groupBy('no_rawat')
        ->get();

        $totalRataAdmisi = $admisi->filter(function($item) {
            return $item->total_menit !== null;
        })->avg('total_menit');
        $totalSeconds = $totalRataAdmisi * 60;

        $formattedTime = gmdate("H:i:s", $totalSeconds);

        // Waktu Tunggu Layanan Admisi
        $layananAdmisi = DB::connection('mysql_khanza')
        ->table('referensi_mobilejkn_bpjs_taskid')
        ->select(
            'no_rawat',
            DB::raw("
                TIMESTAMPDIFF(
                    MINUTE,
                    MIN(CASE WHEN taskid = '2' THEN waktu END),
                    MAX(CASE WHEN taskid = '3' THEN waktu END)
                ) AS total_menit
            ")
        )
        ->whereBetween(DB::raw('DATE(waktu)'), [$startReg, $endReg])
        ->groupBy('no_rawat')
        ->get();

        $totalRataLayananAdmisi = $layananAdmisi->filter(function($item) {
            return $item->total_menit !== null;
        })->avg('total_menit');
        $totalSecondsLayanan = $totalRataLayananAdmisi * 60;

        $formattedTimeLayanan = gmdate("H:i:s", $totalSecondsLayanan);


        return response()->json([
            'admisi' => $admisi,
            'totalRataAdmisi' => $formattedTime,
            'layananAdmisi' => $layananAdmisi,
            'totalRataLayananAdmisi' => $formattedTimeLayanan,
            'status' => 'success'
        ]);
    }

    public function rataPoli(Request $request)
    {
        $startReg = $request->start;
        $endReg   = $request->end;

        // Waktu Tunggu Poli
        $tungguPoli = DB::connection('mysql_khanza')
        ->table('referensi_mobilejkn_bpjs_taskid')
        ->select(
            'no_rawat',
            DB::raw("
                TIMESTAMPDIFF(
                    MINUTE,
                    MIN(CASE WHEN taskid = '3' THEN waktu END),
                    MAX(CASE WHEN taskid = '4' THEN waktu END)
                ) AS total_menit
            ")
        )
        ->whereBetween(DB::raw('DATE(waktu)'), [$startReg, $endReg])
        ->groupBy('no_rawat')
        ->get();

        $totalRataTungguPoli = $tungguPoli->filter(function($item) {
            return $item->total_menit !== null;
        })->avg('total_menit');
        $totalSecondsTungguPoli = $totalRataTungguPoli * 60;

        $formattedTimeTungguPoli = gmdate("H:i:s", $totalSecondsTungguPoli);

        // Waktu Tunggu Layanan Admisi
        $layananPoli = DB::connection('mysql_khanza')
        ->table('referensi_mobilejkn_bpjs_taskid')
        ->select(
            'no_rawat',
            DB::raw("
                TIMESTAMPDIFF(
                    MINUTE,
                    MIN(CASE WHEN taskid = '4' THEN waktu END),
                    MAX(CASE WHEN taskid = '5' THEN waktu END)
                ) AS total_menit
            ")
        )
        ->whereBetween(DB::raw('DATE(waktu)'), [$startReg, $endReg])
        ->groupBy('no_rawat')
        ->get();

        $totalRataLayananPoli = $layananPoli->filter(function($item) {
            return $item->total_menit !== null;
        })->avg('total_menit');
        $totalSecondsLayananPoli = $totalRataLayananPoli * 60;
        $formattedTimeLayananPoli = gmdate("H:i:s", $totalSecondsLayananPoli);


        return response()->json([
            'tungguPoli' => $tungguPoli,
            'totalRataTungguPoli' => $formattedTimeTungguPoli,
            'layananPoli' => $layananPoli,
            'totalRataLayananPoli' => $formattedTimeLayananPoli,
            'status' => 'success'
        ]);
    }

    public function rataFarmasi(Request $request)
    {
        $startReg = $request->start;
        $endReg   = $request->end;

        // Waktu Tunggu Poli
        $tungguFarmasi = DB::connection('mysql_khanza')
        ->table('referensi_mobilejkn_bpjs_taskid')
        ->select(
            'no_rawat',
            DB::raw("
                TIMESTAMPDIFF(
                    MINUTE,
                    MIN(CASE WHEN taskid = '5' THEN waktu END),
                    MAX(CASE WHEN taskid = '6' THEN waktu END)
                ) AS total_menit
            ")
        )
        ->whereBetween(DB::raw('DATE(waktu)'), [$startReg, $endReg])
        ->groupBy('no_rawat')
        ->get();

        $totalRataTungguFarmasi = $tungguFarmasi->filter(function($item) {
            return $item->total_menit !== null;
        })->avg('total_menit');
        $totalSecondsTungguFarmasi = $totalRataTungguFarmasi * 60;

        $formattedTimeTungguFarmasi = gmdate("H:i:s", $totalSecondsTungguFarmasi);

        // Waktu Tunggu Layanan Admisi
        $layananFarmasi = DB::connection('mysql_khanza')
        ->table('referensi_mobilejkn_bpjs_taskid')
        ->select(
            'no_rawat',
            DB::raw("
                TIMESTAMPDIFF(
                    MINUTE,
                    MIN(CASE WHEN taskid = '6' THEN waktu END),
                    MAX(CASE WHEN taskid = '7' THEN waktu END)
                ) AS total_menit
            ")
        )
        ->whereBetween(DB::raw('DATE(waktu)'), [$startReg, $endReg])
        ->groupBy('no_rawat')
        ->get();

        $totalRataLayananFarmasi = $layananFarmasi->filter(function($item) {
            return $item->total_menit !== null;
        })->avg('total_menit');
        $totalSecondsLayananFarmasi = $totalRataLayananFarmasi * 60;
        $formattedTimeLayananFarmasi = gmdate("H:i:s", $totalSecondsLayananFarmasi);


        return response()->json([
            'tungguFarmasi' => $tungguFarmasi,
            'totalRataTungguFarmasi' => $formattedTimeTungguFarmasi,
            'layananFarmasi' => $layananFarmasi,
            'totalRataLayananFarmasi' => $formattedTimeLayananFarmasi,
            'status' => 'success'
        ]);
    }

    public function listTaskId(Request $request)
    {
        return view('SIMRS.taskId.table');
    }

    public function dataTaskId(Request $request)
    {
        $startReg = $request->start_date;
        $endReg   = $request->end_date;

        $data = [];

        $result = DB::connection('mysql_khanza')
            ->table('referensi_mobilejkn_bpjs_taskid as t')
            ->leftJoin('log_taskid as l', 't.no_rawat', '=', 'l.no_rawat')
            ->leftJoin('reg_periksa', 't.no_rawat', '=', 'reg_periksa.no_rawat')
            ->leftJoin('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->leftJoin('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->select(
                't.no_rawat',
                DB::raw('DATE(t.waktu) as tanggal'),
                'l.status_daftar as status',
                'reg_periksa.no_rawat as reg_periksa_no_rawat',
                'reg_periksa.kd_poli as reg_periksa_kd_poli',
                'reg_periksa.no_rkm_medis as reg_periksa_no_rkm_medis',
                'pasien.nm_pasien as nm_pasien',
                'poliklinik.nm_poli as nm_poli'
            )
            ->whereBetween(DB::raw('DATE(t.waktu)'), [$startReg, $endReg])
            ->distinct()
            ->orderBy('tanggal', 'asc')     
            ->orderBy('t.no_rawat', 'asc')  
            ->get();
        
            foreach($result as $r) {
                $data[] = [
                    'no_rawat' => $r->no_rawat,
                    'tanggal' => $r->tanggal,
                    'nm_poli' => $r->nm_poli,
                    'nm_pasien' => $r->nm_pasien,
                    'status' => $r->status,
                ];
            }

        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('actions', function ($data) {
            return '<button class="btn btn-sm btn-primary" onclick="detailTaskid(\'' . $data['no_rawat'] . '\')">  
                        <i class="ri-admin-fill"></i>
                    </button>';
        })
        ->rawColumns(['actions'])
        ->make(true);
    }

    public function detailTaskid(Request $request)
    {
        $noRawat = $request->no_rawat;
        Log::info($noRawat);

        $dataDetail = [];

        $detail = DB::connection('mysql_khanza')
            ->table('referensi_mobilejkn_bpjs_taskid as r')
            ->leftjoin('log_taskid as l', function($join) {
                $join->on('r.no_rawat', '=', 'l.no_rawat')
                    ->on('r.taskid', '=', 'l.task_id');
            })
            ->select(
                'r.no_rawat',
                'r.taskid',
                'r.waktu',
                'l.status',
                'l.log'
            )
            ->where('r.no_rawat', $noRawat)
            ->orderBy('r.taskid', 'asc')
            ->get();

        // kumpulkan data untuk DataTables
        foreach($detail as $d) {
            $dataDetail[] = [
                'no_rawat' => $d->no_rawat,
                'taskid'   => $d->taskid,
                'waktu'    => $d->waktu,
                'status'   => $d->status,
                'log'      => $d->log,
            ];
        }

        // cari total waktu (abaikan taskid 0)
        $waktuAwal = $detail->where('taskid', '!=', 0)->min('waktu');
        $waktuAkhir = $detail->where('taskid', '!=', 0)->max('waktu');

        $totalWaktu = null;
        if ($waktuAwal && $waktuAkhir) {
            $awal = \Carbon\Carbon::parse($waktuAwal);
            $akhir = \Carbon\Carbon::parse($waktuAkhir);
            $totalWaktu = $akhir->diff($awal)->format('%H:%I:%S');
        }

        Log::info($detail);

        return DataTables::of($dataDetail)
            ->addIndexColumn()
            ->with('total_waktu', $totalWaktu) // kirim ke datatable
            ->make(true);
    }

    public function taskIdOnsite(Request $request)
    {
            $startReg = $request->start_date;
            $endReg   = $request->end_date;

            // Normalisasi format dari 2025-08-14 -> 2025/08/14
            $start = str_replace('-', '/', $request->start_date);
            $end   = str_replace('-', '/', $request->end_date);

            $status = $request->status;

            Log::info("Start: $start, End: $end, StartReg: $startReg, EndReg: $endReg");

            if($status == 'terkirim') {
                // $dataOnsite = $this->TaskIdService->getTaskId()
                // ->join('reg_periksa', 'reg_periksa.no_rawat', '=', 'log_taskid.no_rawat')
                // ->join('poliklinik', 'poliklinik.kd_poli', '=', 'reg_periksa.kd_poli')
                // ->join('pasien', 'pasien.no_rkm_medis', '=', 'reg_periksa.no_rkm_medis')
                // ->select(DB::raw("LEFT(log_taskid.no_rawat, 10) as tanggal"), 'log_taskid.no_rawat', 'reg_periksa.no_rkm_medis', 'reg_periksa.kd_poli', 'poliklinik.nm_poli', 'pasien.nm_pasien')
                // ->whereBetween(DB::raw("LEFT(log_taskid.no_rawat, 10)"), [$start, $end])
                // ->where ('status_daftar', 'Onsite')
                // ->groupBy(
                //     'tanggal',
                //     'log_taskid.no_rawat',
                //     'reg_periksa.no_rkm_medis',
                //     'reg_periksa.kd_poli',
                //     'poliklinik.nm_poli',
                //     'pasien.nm_pasien'
                // )
                // ->get();
                $dataOnsite = $this->TaskIdService->getTaskId()
                ->join('reg_periksa', 'reg_periksa.no_rawat', '=', 'log_taskid.no_rawat')
                ->join('poliklinik', 'poliklinik.kd_poli', '=', 'reg_periksa.kd_poli')
                ->join('pasien', 'pasien.no_rkm_medis', '=', 'reg_periksa.no_rkm_medis')
                ->select(
                    DB::raw("LEFT(log_taskid.no_rawat, 10) as tanggal"),
                    'log_taskid.no_rawat',
                    'reg_periksa.no_rkm_medis',
                    'reg_periksa.kd_poli',
                    'poliklinik.nm_poli',
                    'pasien.nm_pasien',
                    DB::raw("
                        CASE 
                            WHEN EXISTS (
                                SELECT 1 FROM log_taskid t
                                WHERE t.no_rawat = log_taskid.no_rawat
                                    AND (
                                        -- Task 0 hanya boleh 200 atau 208
                                        (t.task_id = '0' AND t.status NOT IN ('200','208'))
                                        -- Task 1–7 wajib 200
                                        OR (t.task_id IN ('1','2','3','4','5','6','7') AND t.status <> '200')
                                    )
                            )
                            -- Tambahan: warning kalau tidak ada task 3,4,5
                            OR NOT EXISTS (
                                SELECT 1 FROM log_taskid t 
                                WHERE t.no_rawat = log_taskid.no_rawat AND t.task_id = '3'
                            )
                            OR NOT EXISTS (
                                SELECT 1 FROM log_taskid t 
                                WHERE t.no_rawat = log_taskid.no_rawat AND t.task_id = '4'
                            )
                            OR NOT EXISTS (
                                SELECT 1 FROM log_taskid t 
                                WHERE t.no_rawat = log_taskid.no_rawat AND t.task_id = '5'
                            )
                            THEN 'Warning'
                            ELSE 'Success'
                        END as status
                    ")
                )
                ->whereBetween(DB::raw("LEFT(log_taskid.no_rawat, 10)"), [$start, $end])
                ->where('status_daftar', 'Onsite')
                ->groupBy(
                    'tanggal',
                    'log_taskid.no_rawat',
                    'reg_periksa.no_rkm_medis',
                    'reg_periksa.kd_poli',
                    'poliklinik.nm_poli',
                    'pasien.nm_pasien'
                )
                ->get();

            }else{
                 // Belum Terkirim   
                $dataOnsite = DB::connection('mysql_khanza')
                ->table('reg_periksa as rp')
                ->select('rp.no_rawat', 'rp.no_rkm_medis', 'rp.kd_poli', 'poliklinik.nm_poli', 'pasien.nm_pasien')
                ->join('poliklinik', 'poliklinik.kd_poli', '=', 'rp.kd_poli')
                ->join('pasien', 'pasien.no_rkm_medis', '=', 'rp.no_rkm_medis')
                ->whereBetween(DB::raw("rp.tgl_registrasi"), [$startReg, $endReg])
                ->whereNotIn('rp.no_rawat', function($query) {
                    $query->select('no_rawat')
                        ->from('log_taskid');
                })
                ->whereNotIn('rp.no_rawat', function($query) {
                    $query->select('no_rawat')
                            ->from('referensi_mobilejkn_bpjs');
                })
                ->distinct()
                ->get();
            }
            
            Log::info("belum:". $dataOnsite);

                return DataTables::of($dataOnsite)
                ->addIndexColumn() // ini biar keluar DT_RowIndex\
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-primary" onclick="detailOnsite(\'' . $row->no_rawat . '\')">Detail</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function taskIdMjkn(Request $request)
    {
            $startReg = $request->start_date;
            $endReg   = $request->end_date;

            // Normalisasi format dari 2025-08-14 -> 2025/08/14
            $start = str_replace('-', '/', $request->start_date);
            $end   = str_replace('-', '/', $request->end_date);

            $status = $request->status;

            Log::info("Start: $start, End: $end, StartReg: $startReg, EndReg: $endReg");

            if($status == 'terkirim') {
                $dataMjkn = $this->TaskIdService->getTaskId()
                ->join('reg_periksa', 'reg_periksa.no_rawat', '=', 'log_taskid.no_rawat')
                ->join('poliklinik', 'poliklinik.kd_poli', '=', 'reg_periksa.kd_poli')
                ->join('pasien', 'pasien.no_rkm_medis', '=', 'reg_periksa.no_rkm_medis')
                ->select(DB::raw("LEFT(log_taskid.no_rawat, 10) as tanggal"), 'log_taskid.no_rawat', 'reg_periksa.no_rkm_medis', 'reg_periksa.kd_poli', 'poliklinik.nm_poli', 'pasien.nm_pasien',
                 DB::raw("
                        CASE 
                            WHEN EXISTS (
                                SELECT 1 FROM log_taskid t
                                WHERE t.no_rawat = log_taskid.no_rawat
                                    AND (
                                        -- Task 0 hanya boleh 200 atau 208
                                        (t.task_id = '0' AND t.status NOT IN ('200','208'))
                                        -- Task 1–7 wajib 200
                                        OR (t.task_id IN ('1','2','3','4','5','6','7') AND t.status <> '200')
                                    )
                            )
                            -- Tambahan: warning kalau tidak ada task 3,4,5
                            OR NOT EXISTS (
                                SELECT 1 FROM log_taskid t 
                                WHERE t.no_rawat = log_taskid.no_rawat AND t.task_id = '3'
                            )
                            OR NOT EXISTS (
                                SELECT 1 FROM log_taskid t 
                                WHERE t.no_rawat = log_taskid.no_rawat AND t.task_id = '4'
                            )
                            OR NOT EXISTS (
                                SELECT 1 FROM log_taskid t 
                                WHERE t.no_rawat = log_taskid.no_rawat AND t.task_id = '5'
                            )
                            THEN 'Warning'
                            ELSE 'Success'
                        END as status
                    ")
                    )
                ->whereBetween(DB::raw("LEFT(log_taskid.no_rawat, 10)"), [$start, $end])
                ->where ('status_daftar', 'MJKN')
                ->groupBy(
                    'tanggal',
                    'log_taskid.no_rawat',
                    'reg_periksa.no_rkm_medis',
                    'reg_periksa.kd_poli',
                    'poliklinik.nm_poli',
                    'pasien.nm_pasien'
                ) // grup berdasarkan tanggal + no_rawat
                ->get();
            }else{
                 // Belum Terkirim   
                $dataMjkn = DB::connection('mysql_khanza')
                ->table('reg_periksa as rp')
                ->select('rp.no_rawat', 'rp.no_rkm_medis', 'rp.kd_poli', 'poliklinik.nm_poli', 'pasien.nm_pasien')
                ->join('referensi_mobilejkn_bpjs as jkn', 'rp.no_rawat', '=', 'jkn.no_rawat')
                ->join('poliklinik', 'poliklinik.kd_poli', '=', 'rp.kd_poli')
                ->join('pasien', 'pasien.no_rkm_medis', '=', 'rp.no_rkm_medis')
                ->whereBetween(DB::raw("rp.tgl_registrasi"), [$startReg, $endReg])
                ->where('jkn.status', '!=', 'batal')
                ->whereNotIn('rp.no_rawat', function($query) {
                $query->select('no_rawat')
                    ->from('log_taskid');
                })
                ->whereIn('rp.no_rawat', function($query) {
                    $query->select('no_rawat')
                        ->from('referensi_mobilejkn_bpjs');
                })
                        ->distinct()
                        ->get();
            }
            
            Log::info("Mjkn:". $dataMjkn);

                return DataTables::of($dataMjkn)
                ->addIndexColumn() // ini biar keluar DT_RowIndex
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-primary" onclick="detailOnsite(\'' . $row->no_rawat . '\')">Detail</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function logTaskId(Request $request){
        $noRawat = $request->no_rawat;
        $data = [];
        $dataLog = $this->TaskIdService->getLogTaskIdRw($noRawat);

        foreach($dataLog as $d){
            $data[] = [
                'no_rawat' => $d->no_rawat,
                'task_id' => $d->task_id,
                'status' => $d->status,
                'log' => $d->log
            ];
        }
        Log::info($data);

        return DataTables::of($data)
                ->addIndexColumn() // ini biar keluar DT_RowIndex
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
