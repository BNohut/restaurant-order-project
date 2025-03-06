<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the restaurants.
     */
    public function index()
    {
        $restaurants = Company::with('address')
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(12);

        return view('restaurants.index', compact('restaurants'));
    }

    /**
     * Display the specified restaurant and its menu.
     */
    public function show($uuid)
    {
        $restaurant = Company::with(['address'])
            ->where('uuid', $uuid)
            ->where('is_active', true)
            ->firstOrFail();

        // Get menu items for this restaurant
        $products = Product::where('company_uuid', $restaurant->uuid)
            ->where('is_available', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        // Group products by category
        $productsByCategory = $products->groupBy('category');

        return view('restaurants.show', compact('restaurant', 'productsByCategory'));
    }
}
