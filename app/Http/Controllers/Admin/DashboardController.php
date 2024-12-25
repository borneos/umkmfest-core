<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogEventHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $data = LogEventHistory::selectRaw('event_id, COUNT(*) as total')
          ->groupBy('event_id')
          ->with('event') // Optional if you want category details
          ->get();
        return view('admin.dashboard', compact('data'));
    }
}