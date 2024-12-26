<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\LogEventHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        // $data = LogEventHistory::selectRaw('event_id, COUNT(*) as total')
        //     ->groupBy('event_id')
        //     ->with('event') // Optional if you want category details
        //     ->get();


        // $countCheckin = LogEventHistory::selectRaw('event_id, COUNT(*) as total')
        //     ->groupBy('event_id')
        //     ->whereNotNull('checkin_at')
        //     ->with('event') // Optional if you want category details
        //     ->get();

        $results = LogEventHistory::select(
            'event_id',
            DB::raw('COUNT(*) AS totalPeserta'),  // Menghitung jumlah peserta terdaftar
            DB::raw('COUNT(CASE WHEN checkin_at IS NOT NULL THEN 1 END) AS totalPesertaHadir')  // Menghitung peserta yang hadir
        )
            ->groupBy('event_id')  // Mengelompokkan berdasarkan event_id
            ->get();

        return view('admin.dashboard', compact('results'));
    }
}
