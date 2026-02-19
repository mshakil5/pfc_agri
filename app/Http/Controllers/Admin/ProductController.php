<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use DataTables;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::select(['id', 'title', 'price', 'status', 'image', 'category_id', 'show_in_menu', 'stock_status'])
                ->with(['category.translations', 'translations'])
                ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
                ->latest();

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return $row->translateOrNew(app()->getLocale())->title ?? $row->title;
                })
                ->addColumn('price', fn($row) => '£' . $row->price)
                ->addColumn('category_name', function ($row) {
                    return $row->category ? ($row->category->translateOrNew(app()->getLocale())->name ?? $row->category->name) : '-';
                })
                ->addColumn('image', function ($row) {
                    $src = $row->image ? asset($row->image) : asset('/placeholder.webp');
                    return '<img src="' . $src . '" class="img-thumbnail">';
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status == 1 ? 'checked' : '';
                    return '<div class="form-check form-switch" dir="ltr">
                                <input type="checkbox" class="form-check-input toggle-status"
                                       id="customSwitchStatus' . $row->id . '" data-id="' . $row->id . '" ' . $checked . '>
                                <label class="form-check-label" for="customSwitchStatus' . $row->id . '"></label>
                            </div>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="dropdown">
                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-fill align-middle"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button class="dropdown-item" id="EditBtn" rid="' . $row->id . '">
                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit
                                    </button>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <button class="dropdown-item deleteBtn"
                                        data-delete-url="' . route('product.destroy', $row->id) . '"
                                        data-method="DELETE"
                                        data-table="#productTable">
                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                    </button>
                                </li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['status', 'action', 'image'])
                ->make(true);
        }

        $categories = Category::with('translations')->where('status', 1)->get();
        $tags = Tag::where('status', 1)->get();
        return view('admin.product.index', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'tag_id'      => 'nullable|exists:tags,id',
            'price'       => 'nullable|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ];

        foreach (config('translatable.locales') as $locale) {
            $rules["$locale.title"]             = $locale === 'en' ? 'required|string' : 'nullable|string';
            $rules["$locale.short_description"] = 'nullable|string';
            $rules["$locale.long_description"]  = 'nullable|string';
        }

        $request->validate($rules);

        $product = new Product();
        $product->title             = $request->input('en.title');
        $product->slug              = Str::slug($request->input('en.title'));
        $product->category_id       = $request->category_id;
        $product->tag_id            = $request->tag_id;
        $product->price             = $request->price ?? 0;
        $product->short_description = $request->input('en.short_description');
        $product->long_description  = $request->input('en.long_description');

        if ($request->hasFile('image')) {
            $name = mt_rand(10000000, 99999999) . '.webp';
            $path = public_path('images/products/');
            if (!file_exists($path)) mkdir($path, 0755, true);

            Image::make($request->file('image'))
                ->resize(1200, null, fn($c) => $c->aspectRatio())
                ->encode('webp', 50)
                ->save($path . $name);

            $product->image = '/images/products/' . $name;
        }

        $product->save();

        foreach (config('translatable.locales') as $locale) {
            $t = $product->translateOrNew($locale);
            $t->title             = $request->input("$locale.title") ?? $request->input('en.title');
            $t->short_description = $request->input("$locale.short_description");
            $t->long_description  = $request->input("$locale.long_description");
            $t->save();
        }

        return response()->json(['message' => 'Product created successfully!'], 200);
    }

    public function edit($id)
    {
        $product = Product::with('translations')->findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request)
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'tag_id'      => 'nullable|exists:tags,id',
            'price'       => 'nullable|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ];

        foreach (config('translatable.locales') as $locale) {
            $rules["$locale.title"]             = $locale === 'en' ? 'required|string' : 'nullable|string';
            $rules["$locale.short_description"] = 'nullable|string';
            $rules["$locale.long_description"]  = 'nullable|string';
        }

        $request->validate($rules);

        $product = Product::findOrFail($request->codeid);
        $product->title             = $request->input('en.title');
        $product->slug              = Str::slug($request->input('en.title'));
        $product->category_id       = $request->category_id;
        $product->tag_id            = $request->tag_id;
        $product->price             = $request->price ?? 0;
        $product->short_description = $request->input('en.short_description');
        $product->long_description  = $request->input('en.long_description');

        if ($request->hasFile('image')) {
            if ($product->image && $product->image != '/placeholder.webp' && file_exists(public_path($product->image))) {
                @unlink(public_path($product->image));
            }

            $name = mt_rand(10000000, 99999999) . '.webp';
            $path = public_path('images/products/');
            if (!file_exists($path)) mkdir($path, 0755, true);

            Image::make($request->file('image'))
                ->resize(1200, null, fn($c) => $c->aspectRatio())
                ->encode('webp', 50)
                ->save($path . $name);

            $product->image = '/images/products/' . $name;
        }

        $product->save();

        foreach (config('translatable.locales') as $locale) {
            $t = $product->translateOrNew($locale);
            $t->title             = $request->input("$locale.title") ?? $request->input('en.title');
            $t->short_description = $request->input("$locale.short_description");
            $t->long_description  = $request->input("$locale.long_description");
            $t->save();
        }

        return response()->json(['message' => 'Product updated successfully!'], 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image && $product->image != '/placeholder.webp' && file_exists(public_path($product->image))) {
            @unlink(public_path($product->image));
        }
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully.'], 200);
    }

    public function toggleStatus(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $product->update(['status' => $request->status]);
        return response()->json(['message' => 'Product status updated successfully.'], 200);
    }
}