<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the welcome page with featured restaurants and products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get featured restaurants
        $featuredCompanies = Company::with(['address'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->take(4)
            ->get();

        // Get all active restaurants
        $activeCompanies = Company::with(['address'])
            ->where('is_active', true)
            ->orderBy('name')
            ->take(8)
            ->get();

        // Get featured products
        $featuredProducts = Product::with(['company'])
            ->where('is_featured', true)
            ->where('is_available', true)
            ->take(8)
            ->get();

        return view('welcome', compact('featuredCompanies', 'activeCompanies', 'featuredProducts'));
    }
}
