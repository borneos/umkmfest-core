<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\FormatMeta;
use App\Http\Traits\Merchant;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    use Merchant, FormatMeta;
    public function get_merchants(Request $request)
    {
        $status = $request->status ?? 1;
        $sort = $request->sort ?? 'desc';
        $merchants = $this->queryMerchantList(compact('status', 'sort'));
        $countMerchants = $merchants->count();
        $meta = $this->metaListMerchant([
            'countMerchants' => $countMerchants
        ]);

        if ($countMerchants == 0) {
            return response()->json(['meta' => $meta, 'data' => null]);
        } else {
            return response()->json(['meta' => $meta, 'data' => $this->resultMerchantList($merchants)]);
        }
    }
}
