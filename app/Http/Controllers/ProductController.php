<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // app/Http/Controllers/FrontendController.php

    public function shop($slug = null)
    {
        // 1. Get all categories for the sidebar
        $categories = Category::where('status', 1)->withCount('products')->get();

        // 2. Start the product query
        $query = Product::where('status', 1);

        $currentCategory = null;

        // 3. If a slug is provided, filter by category
        if ($slug) {
            $currentCategory = Category::where('slug', $slug)->firstOrFail();
            $query->where('category_id', $currentCategory->id);
        }

        $products = $query->latest()->paginate(12);

        return view('frontend.shop', compact('categories', 'products', 'currentCategory'));
    }

    public function productDetail()
    {
        return view('frontend.product-detail');
    }



}
