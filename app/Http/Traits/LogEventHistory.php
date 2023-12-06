<?php

namespace App\Http\Traits;

use App\Http\Traits\Event as TraitsEvent;
use App\Models\LogEventHistory as ModelsLogEventHistory;

trait LogEventHistory
{
    use TraitsEvent;

    public function queryEventHistoryList($data)
    {
        $telp = $data['telp'];
        $email = $data['email'];
        $sort = $data['sort'];
        $category = $data['category'];

        if ($category == null) {
            return ModelsLogEventHistory::orwhere('telp', '=', $telp)
                ->orwhere('email', '=', $email)
                ->orderBy('id', $sort)
                ->get();
        } else {
            return ModelsLogEventHistory::orwhere('telp', '=', $telp)
                ->orwhere('event_category', '=', $category)
                ->orderBy('id', $sort)
                ->get();
        }
    }

    public function resultEventList($data)
    {
        foreach ($data as $result) {
            $results[] = [
                'name' => $result->name,
                'telp' => $result->telp,
                'email' => $result->email,
                'events' => [
                    $this->queryEvent($result['event_id'])
                ],
                'checkinAt' => $result->checkin_at,
                'createdAt' => $result->created_at,
                'updateAt' => $result->update_at
            ];
        }
        return $results;
    }
}
