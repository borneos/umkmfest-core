<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\FormatMeta;
use App\Http\Traits\LogGameHistory;
use Illuminate\Http\Request;

class LogGameHistoryController extends Controller
{
    use FormatMeta, LogGameHistory;

    public function get_game_histories(Request $request)
    {
        $telp = $request->telp;
        $email = $request->email;
        $sort = $request->sort ?? 'desc';
        $perPage = $request->perPage ?? 10;
        if ($telp || $email) {
            $gameHistories = $this->queryGameHistoryList(compact('telp', 'email', 'sort'));
            $countGameHistories = $gameHistories->count();
            $meta = $this->metaGameHistory([
                'page' => $request->page == null ? 1 : $request->page,
                'perPage' => $perPage,
                'countGameHistories' => $countGameHistories
            ]);
            if ($countGameHistories == 0) {
                return response()->json(['meta' => $meta, 'data' => null]);
            } else {
                return response()->json(['meta' => $meta, 'data' => $this->resultGameList($gameHistories)]);
            }
        } else {
            $meta = $this->metaGameHistory([
                'success' => false
            ]);
            return response()->json(['meta' => $meta, 'data' => null]);
        }
    }
}
