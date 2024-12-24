<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{

  public function get_coupon_uuid($uuid)
    {
      $data = Coupon::where('code', '=', $uuid)->first();
      return response()->json(['data' => $data]);
    }
}