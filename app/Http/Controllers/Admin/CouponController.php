<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\CloudinaryImage;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CouponController extends Controller
{

  public function index(Request $request)
  {
    $couponQuery = Coupon::query();
    $sortColumn = $request->query('sortColumn');
    $sortDirection = $request->query('sortDirection');
    $searchParam = $request->query('q');

    if ($sortColumn && $sortDirection) {
      $couponQuery->orderBy($sortColumn, $sortDirection ?: 'asc');
    }

    if ($searchParam) {
      $couponQuery = $couponQuery->where(function ($query) use ($searchParam) {
        $query
            ->orWhere('code', 'like', "%$searchParam%")
            ->orWhere('nominal', 'like', "%$searchParam%");
      });
    }

    $coupons = $couponQuery->paginate(9);
    return view('admin.coupons', compact('coupons', 'sortColumn', 'sortDirection', 'searchParam'));
  }

  public function store(Request $request)
    {
        $request->validate([
            'total' => 'required|integer|min:1',
            'nominal' => 'required|integer|min:1',
        ]);

        $total = $request->input('total');
        $nominal = $request->input('nominal');

        for ($i = 0; $i < $total; $i++) {
            Coupon::create([
                'code' => Str::uuid(),
                'nominal' => $nominal,
            ]);
        }

        return redirect()->back()->with('success', 'Coupons generated successfully.');
    }

  public function destroy(Coupon $coupon)
  {
      $coupon->delete();
      return redirect()->route('admin.coupons')->with('success', 'Coupon deleted successfully.');
  }

}
