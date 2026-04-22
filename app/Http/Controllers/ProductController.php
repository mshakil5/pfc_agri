<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

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

        // 4. Search filter
        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                ->orWhere('long_description', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(12);

        return view('frontend.shop', compact('categories', 'products', 'currentCategory'));
    }

    public function productDetail($slug)
    {
        $product = Product::with(['translations', 'category.translations'])
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $locale = app()->getLocale();
        $trans = $product->translateOrNew($locale);

        // Grab all required translated and JSON data
        $features  = $trans->features ?? [];
        $specs     = $trans->specs ?? '';      // HTML content from Summernote
        $downloads = $product->downloads ?? []; // JSON array from DB

        return view('frontend.product-detail', compact('product', 'features', 'specs', 'downloads'));
    }


}
