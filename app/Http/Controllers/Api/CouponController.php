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

    public function complete_coupon(Request $request, $code)
    {
        $coupon = Coupon::where('code', '=', $code)->firstOrFail();

        if (is_null($coupon->complete_at)) {
            $coupon->complete_at = now();
            $coupon->complete_by = $request->name;
            $coupon->complete_telp_by = $request->telp;
            $coupon->save();
            $meta = [
                "status" => "success",
                "statusCode" => 200,
                "statusMessage" => "Berhasil Scan Voucher",
            ];
            return response()->json(['meta' => $meta, 'data' => $coupon]);
        } else {
            $meta = [
                "status" => "error",
                "statusCode" => 500,
                "statusMessage" => "Voucher Telah digunakan!!"
            ];
            return response()->json(['meta' => $meta, 'data' => null]);
        }
    }
}
