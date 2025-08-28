<?php

namespace App\Http\Controllers\simrs\display;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\khanza\reg_periksaModel;

class PoliController extends Controller
{
    public function index()
    {
        return view('SIMRS.display.displaypoli');
    }

    public function data()
    {
        $data = reg_periksaModel::dataByDate();
        return response()->json($data);
    }
    public function lastdata()
    {
        $lastdata = reg_periksaModel::getLatestQueue();
        return response()->json($lastdata);
    }
}
