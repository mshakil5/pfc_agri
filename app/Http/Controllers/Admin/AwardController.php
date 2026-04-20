<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Intervention\Image\Facades\Image;

class AwardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $awards = Award::with('translations')->latest();
            
            return DataTables::of($awards)
                ->addIndexColumn()
                ->addColumn('title', function($row) {
                    return $row->translateOrNew(app()->getLocale())->title;
                })
                ->addColumn('image', function($row) {
                    if ($row->image) {
                        return '<img src="'.asset($row->image).'" class="img-thumbnail" width="50">';
                    }
                    // Fallback to old icon if image doesn't exist yet
                    return $row->icon ? '<i class="'. $row->icon .' fs-2 text-primary"></i>' : '-';
                })
                ->addColumn('action', function($row) {
                    return '
                        <div class="dropdown">
                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown"><i class="ri-more-fill"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button class="dropdown-item" id="EditBtn" rid="'.$row->id.'"><i class="ri-pencil-fill me-2 text-muted"></i> Edit</button></li>
                                <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('awards.destroy', $row->id).'" data-table="#awardTable"><i class="ri-delete-bin-fill me-2 text-muted"></i> Delete</button></li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }
        return view('admin.awards.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'year' => 'required|integer',
            'title' => 'required|string',
            'organization' => 'required|string',
            'tag' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $award = new Award();
        $award->year = $request->year;
        
        if ($request->hasFile('image')) {
            $award->image = $this->uploadImage($request->file('image'));
        }
        
        $award->save();

        $this->saveTranslations($award, $request);

        return response()->json(['message' => 'Award created & translated successfully!']);
    }

    public function edit($id)
    {
        $award = Award::findOrFail($id);
        $enTranslation = $award->translateOrNew('en'); // <--- FIXED BUG HERE
        
        return response()->json([
            'id' => $award->id,
            'year' => $award->year,
            'image' => $award->image,
            'title' => $enTranslation->title,
            'organization' => $enTranslation->organization,
            'tag' => $enTranslation->tag,
            'description' => $enTranslation->description,
        ]);
    }

    public function update(Request $request)
    {
        $award = Award::findOrFail($request->codeid);
        
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Nullable on update
            'year' => 'required|integer',
            'title' => 'required|string',
            'organization' => 'required|string',
            'tag' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $award->year = $request->year;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($award->image && file_exists(public_path($award->image))) {
                @unlink(public_path($award->image));
            }
            $award->image = $this->uploadImage($request->file('image'));
        }

        $award->save();

        $this->saveTranslations($award, $request);

        return response()->json(['message' => 'Award updated & translated successfully!']);
    }

    public function destroy($id)
    {
        $award = Award::findOrFail($id);
        
        // Delete image file from server
        if ($award->image && file_exists(public_path($award->image))) {
            @unlink(public_path($award->image));
        }
        
        $award->delete();
        return response()->json(['message' => 'Award deleted successfully!']);
    }

    private function saveTranslations($award, $request)
    {
        $enTitle = $request->title;
        $enOrganization = $request->organization;
        $enTag = $request->tag;
        $enDescription = $request->description;

        $otherLocales = array_diff(config('translatable.locales'), ['en']);

        foreach (config('translatable.locales') as $locale) {
            $translation = $award->translateOrNew($locale);

            if ($locale === 'en') {
                $translation->title = $enTitle;
                $translation->organization = $enOrganization;
                $translation->tag = $enTag;
                $translation->description = $enDescription;
            } else {
                try {
                    $tr = new GoogleTranslate($locale);
                    $tr->setSource('en');

                    $translation->title = $tr->translate($enTitle);
                    usleep(200000);

                    $translation->organization = $tr->translate($enOrganization);
                    usleep(200000);

                    $translation->tag = !empty($enTag) ? $tr->translate($enTag) : '';
                    usleep(200000);

                    $translation->description = !empty($enDescription) ? $tr->translate($enDescription) : '';
                    usleep(300000); 

                } catch (\Exception $e) {
                    $translation->title = $enTitle;
                    $translation->organization = $enOrganization;
                    $translation->tag = $enTag;
                    $translation->description = $enDescription;
                }
            }
            $translation->save();
        }
    }

    private function uploadImage($file)
    {
        $name = mt_rand(10000000, 99999999) . '.webp';
        $path = public_path('images/awards/');
        if (!file_exists($path)) mkdir($path, 0755, true);

        Image::make($file)
            ->encode('webp', 80)
            ->save($path . $name);

        return '/images/awards/' . $name;
    }
}