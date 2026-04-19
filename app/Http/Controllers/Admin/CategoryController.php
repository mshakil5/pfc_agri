<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use DataTables;
use Intervention\Image\Facades\Image;
use Stichoza\GoogleTranslate\GoogleTranslate;

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
        $request->validate([
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,id',
            'image'       => 'nullable|image',
        ]);

        $data = new Category;
        $data->name      = $request->name;
        $data->slug      = Str::slug($request->name);
        $data->parent_id = $request->parent_id;

        if ($request->hasFile('image')) {
            $data->image = $this->uploadImage($request->file('image'));
        }

        $data->save();

        $this->saveTranslations($data, $request->name, $request->description);

        return response()->json(['message' => 'Category created & translated successfully!'], 200);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $enTranslation = $category->translate('en');
        
        return response()->json([
            'id'          => $category->id,
            'name'        => $enTranslation->name,
            'description' => $enTranslation->description,
            'parent_id'   => $category->parent_id,
            'image'       => $category->image,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,id',
            'image'       => 'nullable|image',
        ]);

        $data = Category::findOrFail($request->codeid);
        $data->name      = $request->name;
        $data->slug      = Str::slug($request->name);
        $data->parent_id = $request->parent_id;

        if ($request->hasFile('image')) {
            if ($data->image && file_exists(public_path($data->image))) {
                @unlink(public_path($data->image));
            }
            $data->image = $this->uploadImage($request->file('image'));
        }

        $data->save();

        $this->saveTranslations($data, $request->name, $request->description);

        return response()->json(['message' => 'Category updated & translated successfully!'], 200);
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
        // Return a clean array with id and the name in the current app locale
        $parentCategories = Category::where('status', 1)->whereNull('parent_id')->latest()->get();
        
        return response()->json(
            $parentCategories->map(function ($cat) {
                return [
                    'id'   => $cat->id,
                    'name' => $cat->translateOrNew(app()->getLocale())->name
                ];
            })
        );
    }

    private function saveTranslations($category, $enName, $enDescription)
    {
        $otherLocales = array_diff(config('translatable.locales'), ['en']);

        foreach (config('translatable.locales') as $locale) {
            $translation = $category->translateOrNew($locale);

            if ($locale === 'en') {
                $translation->name        = $enName;
                $translation->description = $enDescription;
            } else {
                try {
                    $tr = new GoogleTranslate($locale);
                    $tr->setSource('en');

                    $translation->name = $tr->translate($enName);
                    usleep(200000);

                    $translation->description = !empty($enDescription) ? $tr->translate($enDescription) : '';
                    usleep(200000);

                } catch (\Exception $e) {
                    $translation->name        = $enName;
                    $translation->description = $enDescription;
                }
            }
            $translation->save();
        }
    }

    private function uploadImage($file)
    {
        $randomName      = mt_rand(10000000, 99999999) . '.webp';
        $destinationPath = public_path('images/category/');
        if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

        Image::make($file)
            ->resize(800, null, fn($c) => $c->aspectRatio())
            ->encode('webp', 50)
            ->save($destinationPath . $randomName);

        return '/images/category/' . $randomName;
    }
}