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
use Stichoza\GoogleTranslate\GoogleTranslate;

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
                ->addColumn('price', fn($row) => '£' . number_format($row->price, 2))
                ->addColumn('category_name', function ($row) {
                    return $row->category ? ($row->category->translateOrNew(app()->getLocale())->name ?? $row->category->name) : '-';
                })
                ->addColumn('image', function ($row) {
                    $src = $row->image ? asset($row->image) : asset('/placeholder.webp');
                    return '<img src="' . $src . '" class="img-thumbnail" width="50">';
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
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'tag_id'      => 'nullable|exists:tags,id',
            'price'       => 'nullable|numeric|min:0',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'title'       => 'required|string',
        ]);

        $product = new Product();
        $product->title       = $request->title;
        $product->slug        = Str::slug($request->title);
        $product->category_id = $request->category_id;
        $product->tag_id      = $request->tag_id;
        $product->price       = $request->price ?? 0;

        // Handle Main Thumbnail
        if ($request->hasFile('image')) {
            $product->image = $this->uploadImage($request->file('image'));
        }

        // Handle Multiple Gallery Images
        $galleryImages = [];
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $galleryImages[] = $this->uploadImage($file);
            }
        }
        $product->images = $galleryImages;

        $product->save();

        // Save Translations & Features
        $this->saveTranslations($product, $request->title, $request->short_description, $request->long_description, $request->features);

        return response()->json(['message' => 'Product created & translated successfully!'], 200);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $enTranslation = $product->translateOrNew('en'); // <--- FIXED
        
        return response()->json([
            'id'                => $product->id,
            'title'             => $enTranslation->title,
            'short_description' => $enTranslation->short_description,
            'long_description'  => $enTranslation->long_description,
            'features'          => $enTranslation->features ?? [],
            'category_id'       => $product->category_id,
            'tag_id'            => $product->tag_id,
            'price'             => $product->price,
            'image'             => $product->image,
            'images'            => $product->images ?? [],
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'tag_id'      => 'nullable|exists:tags,id',
            'price'       => 'nullable|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'title'       => 'required|string',
        ]);

        $product = Product::findOrFail($request->codeid);
        $product->title       = $request->title;
        $product->slug        = Str::slug($request->title);
        $product->category_id = $request->category_id;
        $product->tag_id      = $request->tag_id;
        $product->price       = $request->price ?? 0;

        // Handle Main Thumbnail Update
        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path($product->image))) {
                @unlink(public_path($product->image));
            }
            $product->image = $this->uploadImage($request->file('image'));
        }

        // Handle Gallery Images Update
        $existingImages = $request->input('existing_images', []);
        $deleteImages = $request->input('delete_images', []);

        // Delete specified images from server
        foreach ($deleteImages as $delImg) {
            if (file_exists(public_path($delImg))) {
                @unlink(public_path($delImg));
            }
        }

        // Keep images that were not marked for deletion
        $finalImages = array_diff($existingImages, $deleteImages);

        // Upload new images and merge
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $finalImages[] = $this->uploadImage($file);
            }
        }
        $product->images = array_values($finalImages);

        $product->save();

        // Save Translations & Features
        $this->saveTranslations($product, $request->title, $request->short_description, $request->long_description, $request->features);

        return response()->json(['message' => 'Product updated & translated successfully!'], 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Delete Main Image
        if ($product->image && file_exists(public_path($product->image))) {
            @unlink(public_path($product->image));
        }
        
        // Delete All Gallery Images
        if ($product->images) {
            foreach ($product->images as $img) {
                if (file_exists(public_path($img))) @unlink(public_path($img));
            }
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

    private function saveTranslations($product, $enTitle, $enShortDesc, $enLongDesc, $enFeatures)
    {
        $otherLocales = array_diff(config('translatable.locales'), ['en']);

        foreach (config('translatable.locales') as $locale) {
            $translation = $product->translateOrNew($locale);

            if ($locale === 'en') {
                $translation->title             = $enTitle;
                $translation->short_description = $enShortDesc;
                $translation->long_description  = $enLongDesc;
                $translation->features          = array_values(array_filter($enFeatures ?? []));
            } else {
                try {
                    $tr = new GoogleTranslate($locale);
                    $tr->setSource('en');

                    $translation->title = $tr->translate($enTitle);
                    usleep(200000);

                    $translation->short_description = !empty($enShortDesc) ? $tr->translate($enShortDesc) : '';
                    usleep(200000);

                    $translation->long_description = !empty($enLongDesc) ? $tr->translate($enLongDesc) : '';
                    usleep(300000);

                    // Translate features list
                    $translatedFeatures = [];
                    foreach (array_filter($enFeatures ?? []) as $feature) {
                        $translatedFeatures[] = $tr->translate($feature);
                        usleep(200000);
                    }
                    $translation->features = $translatedFeatures;

                } catch (\Exception $e) {
                    $translation->title             = $enTitle;
                    $translation->short_description = $enShortDesc;
                    $translation->long_description  = $enLongDesc;
                    $translation->features          = array_values(array_filter($enFeatures ?? []));
                }
            }
            $translation->save();
        }
    }

    private function uploadImage($file)
    {
        $name = mt_rand(10000000, 99999999) . '.webp';
        $path = public_path('images/products/');
        if (!file_exists($path)) mkdir($path, 0755, true);

        Image::make($file)
            ->resize(1200, null, fn($c) => $c->aspectRatio())
            ->encode('webp', 50)
            ->save($path . $name);

        return '/images/products/' . $name;
    }
}