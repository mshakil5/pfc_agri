<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Master;
use App\Models\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DataTables;
use Intervention\Image\Facades\Image;

class ResearchController extends Controller
{
    public function research(Request $request)
    {
        $data = Master::where('pages', 'rnd')->first();
        return view('admin.research.index', compact('data'));
    }

    public function researchUpdate(Request $request)
    {
        $data = Master::find($request->codeid);

        if (!$data) {
            return response()->json(['message' => 'Record not found!'], 404);
        }

        $data->name        = $request->name;
        $data->short_title = $request->short_title;
        $data->long_title  = $request->long_title;
        $data->meta_title       = $request->meta_title;
        $data->meta_description = $request->meta_description;
        $data->meta_keywords    = $request->meta_keywords;

        if ($request->hasFile('meta_image')) {
            if ($data->meta_image && file_exists(public_path('images/meta/' . $data->meta_image))) {
                @unlink(public_path('images/meta/' . $data->meta_image));
            }
            $name = mt_rand(10000000, 99999999) . '.webp';
            $path = public_path('images/meta/');
            if (!file_exists($path)) mkdir($path, 0755, true);
            Image::make($request->file('meta_image'))->encode('webp', 80)->save($path . $name);
            $data->meta_image = $name;
        }

        $data->extra1 = json_encode(
            $request->has('features') ? array_values($request->features) : []
        );

        // Save translations
        $translations = $data->translations ?? [];
        foreach (config('translatable.locales') as $locale) {
            if ($locale === 'en') continue;
            $translations[$locale] = [
                'name'        => $request->input("trans.$locale.name"),
                'short_title' => $request->input("trans.$locale.short_title"),
                'long_title'  => $request->input("trans.$locale.long_title"),
                'counters'    => array_values(array_filter(
                    $request->input("trans.$locale.counters", []),
                    fn($c) => !empty($c['subtitle'])
                )),
            ];
        }
        $data->translations = $translations;

        if ($data->save()) {
            return response()->json(['status' => 200, 'message' => 'Data updated successfully!']);
        }

        return response()->json(['message' => 'Failed to update data'], 500);
    }

    public function initiatives(Request $request)
    {
        if ($request->ajax()) {
            $products = Research::select(['id', 'title', 'status', 'feature_image'])->latest();
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('feature_image', function ($row) {
                    $src = $row->feature_image ? asset($row->feature_image) : asset('/placeholder.webp');
                    return '<img src="' . $src . '" class="img-thumbnail">';
                })
                ->addColumn('status', function ($row) {
                    $statusLabels = [
                        0 => ['label' => 'Planning',    'class' => 'bg-info'],
                        1 => ['label' => 'In Progress', 'class' => 'bg-primary'],
                        2 => ['label' => 'Testing',     'class' => 'bg-warning text-dark'],
                        3 => ['label' => 'Complete',    'class' => 'bg-success'],
                        4 => ['label' => 'Decline',     'class' => 'bg-danger'],
                    ];
                    $current = $statusLabels[$row->status] ?? ['label' => 'Unknown', 'class' => 'bg-secondary'];
                    return '<span class="badge ' . $current['class'] . '">' . $current['label'] . '</span>';
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
                                        data-delete-url="' . route('initiatives.destroy', $row->id) . '"
                                        data-method="DELETE"
                                        data-table="#productTable">
                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                    </button>
                                </li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['status', 'action', 'feature_image'])
                ->make(true);
        }

        return view('admin.research.initiatives');
    }

    public function store(Request $request)
    {
        $request->merge(['title' => trim($request->title)]);
        $request->validate([
            'title'         => 'required|unique:research,title',
            'date'          => 'nullable|date',
            'deadline'      => 'nullable|date',
            'status'        => 'required|integer',
            'feature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = new Research();
        $this->saveData($data, $request);

        return response()->json(['message' => 'Initiative created successfully!'], 200);
    }

    public function edit($id)
    {
        $data = Research::findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request)
    {
        $request->merge(['title' => trim($request->title)]);
        $request->validate([
            'codeid'        => 'required|exists:research,id',
            'title'         => 'required|unique:research,title,' . $request->codeid,
            'date'          => 'nullable|date',
            'deadline'      => 'nullable|date',
            'status'        => 'required|integer',
            'feature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = Research::findOrFail($request->codeid);
        $this->saveData($data, $request);

        return response()->json(['message' => 'Initiative updated successfully!'], 200);
    }

    private function saveData($model, $request)
    {
        $model->title             = $request->title;
        $model->status            = $request->status;
        $model->date              = $request->date;
        $model->deadline          = $request->deadline;
        $model->short_description = $request->short_description;
        $model->long_description  = $request->long_description;
        $model->meta_title        = $request->meta_title;
        $model->meta_description  = $request->meta_description;
        $model->meta_keywords     = $request->meta_keywords;

        if ($request->hasFile('feature_image')) {
            $model->feature_image = $this->uploadImage($request->file('feature_image'), 'images/research/', $model->feature_image ?? null);
        }
        if ($request->hasFile('meta_image')) {
            $model->meta_image = $this->uploadImage($request->file('meta_image'), 'images/meta/', $model->meta_image ?? null);
        }

        // Save translations
        $translations = $model->translations ?? [];
        foreach (config('translatable.locales') as $locale) {
            if ($locale === 'en') continue;
            $translations[$locale] = [
                'title'             => $request->input("trans.$locale.title"),
                'short_description' => $request->input("trans.$locale.short_description"),
                'long_description'  => $request->input("trans.$locale.long_description"),
            ];
        }
        $model->translations = $translations;

        $model->save();
    }

    private function uploadImage($file, $folder, $oldPath = null)
    {
        if ($oldPath && !str_contains($oldPath, 'placeholder') && file_exists(public_path($oldPath))) {
            @unlink(public_path($oldPath));
        }
        $name = mt_rand(10000000, 99999999) . '.webp';
        $path = public_path($folder);
        if (!file_exists($path)) mkdir($path, 0755, true);
        Image::make($file)->resize(1200, null, fn($c) => $c->aspectRatio())->encode('webp', 60)->save($path . $name);
        return '/' . $folder . $name;
    }

    public function destroy($id)
    {
        $data = Research::findOrFail($id);
        if ($data->feature_image && $data->feature_image != '/placeholder.webp' && file_exists(public_path($data->feature_image))) {
            @unlink(public_path($data->feature_image));
        }
        $data->delete();
        return response()->json(['message' => 'Research deleted successfully.'], 200);
    }

    public function toggleStatus(Request $request)
    {
        $data = Research::findOrFail($request->product_id);
        $data->update(['status' => $request->status]);
        return response()->json(['message' => 'Research status updated successfully.'], 200);
    }
}