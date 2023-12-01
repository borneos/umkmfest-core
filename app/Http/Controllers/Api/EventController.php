<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\event;
use App\Http\Traits\FormatMeta;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use event, FormatMeta;

    public function get_events(Request $request)
    {
        $status = $request->status ?? 1;
        $sort = $request->sort ?? 'desc';
        $category = $request->category ?? null;
        $perPage = $request->perPage ?? 10;
        $events = $this->queryEventList(compact('status', 'sort', 'perPage', 'category'));
        $countEvents = $events->count();
        $meta = $this->metaListEvent([
            'page' => $request->page == null ? 1 : $request->page,
            'perPage' => $perPage,
            'countEvents' => $countEvents
        ]);

        if ($countEvents == 0) {
            return response()->json(['meta' => $meta, 'data' => null]);
        } else {
            return response()->json(['meta' => $meta, 'data' => $this->resultEventList($events)]);
        }
    }
}
