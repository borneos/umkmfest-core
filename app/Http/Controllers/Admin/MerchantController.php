<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\CloudinaryImage;
use App\Models\Merchant;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    use CloudinaryImage;

    public function index(Request $request)
    {
        $merchantQuery = Merchant::query();
        $sortColumn = $request->query('sortColumn');
        $sortDirection = $request->query('sortDirection');
        $searchParam = $request->query('q');

        if ($sortColumn && $sortDirection) {
            $merchantQuery->orderBy($sortColumn, $sortDirection ?: 'asc');
        }

        if ($searchParam) {
            $merchantQuery = $merchantQuery->where(function ($query) use ($searchParam) {
                $query
                    ->orWhere('name', 'like', "%$searchParam%");
            });
        }

        $merchants = $merchantQuery->paginate(5);
        return view('admin.merchants', compact('merchants', 'sortColumn', 'sortDirection', 'searchParam'));
    }
}
