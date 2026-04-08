<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $purchaseQuery = Purchase::query();

        if (! $request->user()->isAdmin()) {
            $purchaseQuery->where('user_id', $request->user()->id);
        }

        $today = now()->toDateString();

        $stats = [
            'product_count' => Product::count(),
            'low_stock_count' => Product::where('stock', '<', 5)->count(),
            'member_count' => Member::count(),
            'today_count' => (clone $purchaseQuery)->whereDate('purchase_date', $today)->count(),
            'today_income' => (clone $purchaseQuery)->whereDate('purchase_date', $today)->sum('total_after_discount'),
        ];

        $latestPurchases = $purchaseQuery
            ->with(['member', 'user'])
            ->latest('purchase_date')
            ->limit(8)
            ->get();

        return view('dashboard', [
            'stats' => $stats,
            'latestPurchases' => $latestPurchases,
        ]);
    }
}
