<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\Banner as TraitsBanner;
use App\Http\Traits\FormatMeta;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use TraitsBanner, FormatMeta;
    public function get_banners(Request $request)
    {
        $status = $request->status ?? 1;
        $sort =  $request->sort ?? 'desc';
        $banners = $this->queryBannerList(compact('status', 'sort'));
        $countBanners = $banners->count();

        if ($countBanners == 0) {
            return response()->json(['meta' => $this->metaListBanner(['countBanners' => $countBanners]), 'data' => null]);
        } else {
            return response()->json(['meta' => $this->metaListBanner(['countBanners' => $countBanners]), 'data' => $this->resultBannerList($banners)]);
        }
    }
}
