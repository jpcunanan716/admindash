<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class AdminController extends Controller
{
    public function index()
    {
        // Analytics data
        $totalProducts = Product::count();
        $totalCategories = Product::distinct()->count('category');
        $productsThisMonth = Product::whereMonth('dateTime', now()->month)->count();
        $latestProduct = Product::latest('created_at')->first();

        // Products per category
        $productsPerCategory = Product::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->get();
        $categoryLabels = $productsPerCategory->pluck('category');
        $categoryValues = $productsPerCategory->pluck('total');

        // Products trend (last 6 months)
        $productsTrend = Product::selectRaw('MONTH(dateTime) as month, COUNT(*) as total')
            ->where('dateTime', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $trendLabels = $productsTrend->pluck('month');
        $trendValues = $productsTrend->pluck('total');

        return view('admin.dashboard', compact(
            'totalProducts', 
            'totalCategories', 
            'productsThisMonth', 
            'latestProduct',
            'categoryLabels',
            'categoryValues',
            'trendLabels',
            'trendValues'
        ));
    }
}
