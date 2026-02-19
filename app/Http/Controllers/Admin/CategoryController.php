<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use DataTables;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::with(['parent', 'translations'])->select(['id', 'name', 'image', 'parent_id', 'status'])->orderBy('id', 'desc');

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->translateOrNew(app()->getLocale())->name ?? $row->name;
                })
                ->addColumn('image', function ($row) {
                    return $row->image
                        ? '<img src="' . url($row->image) . '" class="img-thumbnail" style="max-width: 80px;">'
                        : '';
                })
                ->addColumn('parent_category', function ($row) {
                    return $row->parent ? ($row->parent->translateOrNew(app()->getLocale())->name ?? $row->parent->name) : '<span class="text-muted">None</span>';
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
                                <li>
                                    <button class="dropdown-item" id="EditBtn" rid="' . $row->id . '">
                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit
                                    </button>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <button class="dropdown-item deleteBtn"
                                            data-delete-url="' . route('category.delete', $row->id) . '"
                                            data-method="DELETE"
                                            data-table="#categoryTable">
                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                    </button>
                                </li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['image', 'parent_category', 'status', 'action'])
                ->make(true);
        }

        $parentCategories = Category::with('translations')->whereNull('parent_id')->where('status', 1)->get();
        return view('admin.category.index', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'parent_id' => 'nullable|exists:categories,id',
            'image'     => 'nullable|image',
        ];

        foreach (config('translatable.locales') as $locale) {
            $rules["$locale.name"] = $locale === 'en' ? 'required|string' : 'nullable|string';
            $rules["$locale.description"] = 'nullable|string';
        }

        $request->validate($rules);

        $data = new Category;
        $data->name        = $request->input('en.name');
        $data->description = $request->input('en.description');
        $data->slug        = Str::slug($request->input('en.name'));
        $data->parent_id   = $request->parent_id;

        if ($request->hasFile('image')) {
            $randomName      = mt_rand(10000000, 99999999) . '.webp';
            $destinationPath = public_path('images/category/');
            if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

            Image::make($request->file('image'))
                ->resize(800, null, fn($c) => $c->aspectRatio())
                ->encode('webp', 50)
                ->save($destinationPath . $randomName);

            $data->image = '/images/category/' . $randomName;
        }

        $data->save();

        foreach (config('translatable.locales') as $locale) {
            $t = $data->translateOrNew($locale);
            $t->name        = $request->input("$locale.name") ?? $request->input('en.name');
            $t->description = $request->input("$locale.description");
            $t->save();
        }

        return response()->json(['message' => 'Category created successfully!', 'category' => $data], 200);
    }

    public function edit($id)
    {
        $category = Category::with('translations')->findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request)
    {
        $rules = [
            'parent_id' => 'nullable|exists:categories,id',
            'image'     => 'nullable|image',
        ];

        foreach (config('translatable.locales') as $locale) {
            $rules["$locale.name"] = $locale === 'en' ? 'required|string' : 'nullable|string';
            $rules["$locale.description"] = 'nullable|string';
        }

        $request->validate($rules);

        $data = Category::findOrFail($request->codeid);
        $data->name        = $request->input('en.name');
        $data->description = $request->input('en.description');
        $data->slug        = Str::slug($request->input('en.name'));
        $data->parent_id   = $request->parent_id;

        if ($request->hasFile('image')) {
            if ($data->image && file_exists(public_path($data->image))) {
                @unlink(public_path($data->image));
            }
            $randomName      = mt_rand(10000000, 99999999) . '.webp';
            $destinationPath = public_path('images/category/');
            if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

            Image::make($request->file('image'))
                ->resize(800, null, fn($c) => $c->aspectRatio())
                ->encode('webp', 50)
                ->save($destinationPath . $randomName);

            $data->image = '/images/category/' . $randomName;
        }

        $data->save();

        foreach (config('translatable.locales') as $locale) {
            $t = $data->translateOrNew($locale);
            $t->name        = $request->input("$locale.name") ?? $request->input('en.name');
            $t->description = $request->input("$locale.description");
            $t->save();
        }

        return response()->json(['message' => 'Category updated successfully!'], 200);
    }

    public function delete($id)
    {
        $data = Category::find($id);

        if (!$data) return response()->json(['message' => 'Category not found.'], 404);

        if (Category::where('parent_id', $id)->exists()) {
            return response()->json(['message' => 'Cannot delete. It has subcategories.'], 422);
        }

        if ($data->image && file_exists(public_path($data->image))) {
            @unlink(public_path($data->image));
        }

        $data->delete();
        return response()->json(['message' => 'Category deleted successfully.'], 200);
    }

    public function toggleStatus(Request $request)
    {
        $category = Category::findOrFail($request->category_id);
        $category->status = $request->status;
        $category->save();
        return response()->json(['message' => 'Category status updated successfully'], 200);
    }

    public function parentCategories()
    {
        $parentCategories = Category::with('translations')->where('status', 1)->select('id', 'name')->latest()->get();
        return response()->json($parentCategories);
    }
}