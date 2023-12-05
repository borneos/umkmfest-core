<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\FormatMeta;
use Illuminate\Http\Request;

class LogGameHistoryController extends Controller
{
    use FormatMeta;

    public function get_event_histories(Request $request)
    {
        $telp = $request->telp;
        $email = $request->email;
        $sort = $request->sort ?? 'desc';

        if ($telp || $email) {
            $eventHistories = $this->queryEventHistoryList(compact('telp', 'email', 'category', 'sort'));
            $meta = $this->metaEventHistory([
                'success' => true
            ]);
            return response()->json(['meta' => $meta, 'data' => $this->resultEventList($eventHistories)]);
        } else {
            $meta = $this->metaEventHistory([
                'success' => false
            ]);
            return response()->json(['meta' => $meta, 'data' => null]);
        }
    }
}
