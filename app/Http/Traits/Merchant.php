<?php

namespace App\Http\Traits;

use App\Models\Merchant as ModelsMerchant;

trait Merchant
{
    public function queryMerchantList($data)
    {
        $sort = $data['sort'];
        $status = $data['status'];

        return ModelsMerchant::where('status', '=', $status)
            ->orderBy('id', $sort)->get();
    }

    public function resultMerchantList($data)
    {
        foreach ($data as $result) {
            $results[] = [
                'name' => $result->name,
                'slug' => $result->slug,
                'description' => $result->description,
                'image' => $result->image,
                'imageAdditional' => $result->image_additional,
                'status' => $result->status,
                'createdAt' => $result->created_at,
                'updateAt' => $result->update_at
            ];
        }
        return $results;
    }
}
