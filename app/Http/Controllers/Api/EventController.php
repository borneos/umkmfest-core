<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\Event as TraitsEvent;
use App\Http\Traits\FormatMeta;
use App\Models\Event as ModelsEvent;
use App\Models\LogEventHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    use TraitsEvent, FormatMeta;

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

    public function detail_events($slug)
    {
        $events = $this->queryDetailEvent(compact('slug'));
        $countEvents = $events == null ? 0 : $events->count();
        $meta = $this->metaDetailEvent([
            'countEvents' => $countEvents
        ]);

        if (!$events) {
            return response()->json(['meta' => $meta, 'data' => null]);
        } else {
            return response()->json(['meta' => $meta, 'data' => $this->resultEventDetail($events)]);
        }
    }

    public function store_log_events(Request $request)
    {
        $cekEvent = LogEventHistory::where('event_id', '=', $request->eventId)
            ->orwhere('email', '=', $request['email'])
            ->orwhere('telp', '=', $request['telp'])
            ->first();

        if (!$cekEvent) {
            $event = ModelsEvent::where('id', '=', $request->eventId)->first();
            if ($event->count() != 0) {

                LogEventHistory::create([
                    'event_id' => $request->eventId,
                    'event_name' => $event->name,
                    'event_category' => $event->category,
                    'event_date' => $event->date,
                    'name' => $request->name,
                    'telp' => $request->telp,
                    'email' => $request->email
                ]);
                return response()->json($this->metaStoreLogEvent());
            }
        } else {
            return response()->json([
                'status' => 'error',
                'statusCode' => 400,
                'statusMessage' => 'Gagal mendaftar, Email atau No Telepon telah digunakan!!!'
            ]);
        }
    }
}
