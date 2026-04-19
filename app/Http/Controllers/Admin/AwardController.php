<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Stichoza\GoogleTranslate\GoogleTranslate;

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
                ->make(true);
        }
        return view('admin.awards.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'icon' => 'required',
            'year' => 'required|integer',
            'title' => 'required|string',
            'organization' => 'required|string',
            'tag' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $award = new Award();
        $award->icon = $request->icon;
        $award->year = $request->year;
        $award->save();

        $this->saveTranslations($award, $request);

        return response()->json(['message' => 'Award created & translated successfully!']);
    }

    public function edit($id)
    {
        $award = Award::with('translations')->findOrFail($id);
        $enTranslation = $award->translate('en');
        
        return response()->json([
            'id' => $award->id,
            'icon' => $award->icon,
            'year' => $award->year,
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
            'icon' => 'required',
            'year' => 'required|integer',
            'title' => 'required|string',
            'organization' => 'required|string',
            'tag' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $award->icon = $request->icon;
        $award->year = $request->year;
        $award->save();

        $this->saveTranslations($award, $request);

        return response()->json(['message' => 'Award updated & translated successfully!']);
    }

    public function destroy($id)
    {
        Award::destroy($id);
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

                    // Slightly longer delay for HTML descriptions
                    $translation->description = !empty($enDescription) ? $tr->translate($enDescription) : '';
                    usleep(300000); 

                } catch (\Exception $e) {
                    // Fallback to English if translation fails
                    $translation->title = $enTitle;
                    $translation->organization = $enOrganization;
                    $translation->tag = $enTag;
                    $translation->description = $enDescription;
                }
            }
            $translation->save();
        }
    }
}