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

    public function queryMerchantDetail($data)
    {
        return ModelsMerchant::where('slug', '=', $data['slug'])->first();
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

    public function resultMerchantDetail($data)
    {
        $results[] = [
            'name' => $data->name,
            'slug' => $data->slug,
            'description' => $data->description,
            'image' => $data->image,
            'imageAdditional' => $data->image_additional,
            'status' => $data->status,
            'createdAt' => $data->created_at,
            'updateAt' => $data->update_at
        ];
        return $results;
    }
}
