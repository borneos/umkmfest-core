<?php

namespace App\Http\Traits;

use App\Models\Banner as ModelsBanner;

trait Banner
{
    public function queryBannerList($data)
    {
        $sort = $data['sort'];
        $status = $data['status'];

        return ModelsBanner::where('status', '=', $status)
            ->orderBy('id', $sort)->get();
        // return ModelsBanner::all();
    }

    public function resultBannerList($data)
    {
        foreach ($data as $result) {
            $results[] = [
                'name' => $result->name,
                'image' => $result->image,
                'imageAdditional' => $result->image_additional,
                'link' => $result->link,
                'status' => $result->status,
                'createdAt' => $result->created_at,
                'updateAt' => $result->update_at
            ];
        }
        return $results;
    }
}
