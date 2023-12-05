<?php

namespace App\Http\Traits;

use App\Models\LogGameHistory as ModelsLogGameHistory;

trait LogGameHistory
{
    use Event;

    public function querGameHistoryList($data)
    {
        $telp = $data['telp'];
        $email = $data['email'];
        $sort = $data['sort'];

        return ModelsLogGameHistory::orwhere('telp', '=', $telp)
            ->orwhere('email', '=', $email)
            ->orderBy('id', $sort)
            ->get();
    }

    public function resultEventList($data)
    {
        foreach ($data as $result) {
            $results[] = [
                'events' => [
                    $this->queryEvent($result['event_id'])
                ],
                // 'name' => $result->name,
                // 'telp' => $result->telp,
                // 'email' => $result->email,
                // 'checkinAt' => $result->checkin_at,
                // 'createdAt' => $result->created_at,
                // 'updateAt' => $result->update_at
            ];
        }
        return $results;
    }
}
