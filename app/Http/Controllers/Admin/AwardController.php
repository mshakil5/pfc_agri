<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Award;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AwardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // We eager load translations to avoid N+1 issues
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
        $data = $this->validateAward($request);
        Award::create($data);
        return response()->json(['message' => 'Award created successfully!']);
    }

    public function edit($id)
    {
        // returns the award with all its 5 language translations
        return Award::with('translations')->findOrFail($id);
    }

    public function update(Request $request)
    {
        $award = Award::findOrFail($request->codeid);
        $data = $this->validateAward($request);
        $award->update($data);
        return response()->json(['message' => 'Award updated successfully!']);
    }

    public function destroy($id)
    {
        Award::destroy($id);
        return response()->json(['message' => 'Award deleted successfully!']);
    }

    private function validateAward($request) {
        $rules = [
            'icon' => 'required',
            'year' => 'required|integer',
        ];

        // Loop through locales to validate each language input
        foreach (config('translatable.locales') as $locale) {
            $rules["$locale.title"] = 'required|string';
            $rules["$locale.organization"] = 'required|string';
            $rules["$locale.tag"] = 'nullable|string';
            $rules["$locale.description"] = 'nullable|string';
        }

        return $request->validate($rules);
    }
}