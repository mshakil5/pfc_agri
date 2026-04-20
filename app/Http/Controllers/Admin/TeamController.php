<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use DataTables;
use Intervention\Image\Facades\Image;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $members = TeamMember::latest();

            return DataTables::of($members)
                ->addIndexColumn()
                ->addColumn('name', fn($row) => $row->name)
                ->addColumn('image', fn($row) => $row->image ? '<img src="'.asset($row->image).'" class="img-thumbnail rounded-circle" width="50">' : '-')
                ->addColumn('designation', fn($row) => $row->translateOrNew(app()->getLocale())->designation)
                ->addColumn('status', function ($row) {
                    $checked = $row->status ? 'checked' : '';
                    return '<div class="form-check form-switch" dir="ltr">
                                <input type="checkbox" class="form-check-input toggle-status"
                                       data-id="' . $row->id . '" ' . $checked . '>
                                <label class="form-check-label"></label>
                            </div>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="dropdown">
                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown"><i class="ri-more-fill"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button class="dropdown-item" id="EditBtn" rid="'.$row->id.'"><i class="ri-pencil-fill me-2 text-muted"></i> Edit</button></li>
                                <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('admin.team.destroy', $row->id).'" data-table="#teamTable"><i class="ri-delete-bin-fill me-2 text-muted"></i> Delete</button></li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }
        return view('admin.team.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'designation' => 'required|string',
            'bio' => 'nullable|string',
        ]);

        $member = new TeamMember();
        $member->name = $request->name; // STRICTLY NON-TRANSLATABLE
        $member->phone = $request->phone;
        $member->email = $request->email;
        $member->serial = TeamMember::max('serial') + 1;

        if ($request->hasFile('image')) {
            $member->image = $this->uploadImage($request->file('image'));
        }

        $member->save();

        $this->saveTranslations($member, $request->designation, $request->bio);

        return response()->json(['message' => 'Team member created & translated successfully!']);
    }

    public function edit($id)
    {
        $member = TeamMember::findOrFail($id);
        $en = $member->translateOrNew('en');
        
        return response()->json([
            'id' => $member->id,
            'name' => $member->name,
            'phone' => $member->phone,
            'email' => $member->email,
            'image' => $member->image,
            'designation' => $en->designation,
            'bio' => $en->bio,
        ]);
    }

    public function update(Request $request)
    {
        $member = TeamMember::findOrFail($request->codeid);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'designation' => 'required|string',
            'bio' => 'nullable|string',
        ]);

        $member->name = $request->name;
        $member->phone = $request->phone;
        $member->email = $request->email;

        if ($request->hasFile('image')) {
            if ($member->image && file_exists(public_path($member->image))) {
                @unlink(public_path($member->image));
            }
            $member->image = $this->uploadImage($request->file('image'));
        }

        $member->save();

        $this->saveTranslations($member, $request->designation, $request->bio);

        return response()->json(['message' => 'Team member updated & translated successfully!']);
    }

    public function destroy($id)
    {
        $member = TeamMember::findOrFail($id);
        if ($member->image && file_exists(public_path($member->image))) {
            @unlink(public_path($member->image));
        }
        $member->delete();
        return response()->json(['message' => 'Team member deleted successfully.']);
    }

    private function saveTranslations($member, $enDesignation, $enBio)
    {
        $otherLocales = array_diff(config('translatable.locales'), ['en']);

        foreach (config('translatable.locales') as $locale) {
            $translation = $member->translateOrNew($locale);

            if ($locale === 'en') {
                $translation->designation = $enDesignation;
                $translation->bio = $enBio;
            } else {
                try {
                    $tr = new GoogleTranslate($locale);
                    $tr->setSource('en');

                    $translation->designation = $tr->translate($enDesignation);
                    usleep(200000);

                    $translation->bio = !empty($enBio) ? $tr->translate($enBio) : '';
                    usleep(300000);

                } catch (\Exception $e) {
                    $translation->designation = $enDesignation;
                    $translation->bio = $enBio;
                }
            }
            $translation->save();
        }
    }

    private function uploadImage($file)
    {
        $name = mt_rand(10000000, 99999999) . '.webp';
        $path = public_path('images/team/');
        if (!file_exists($path)) mkdir($path, 0755, true);
        Image::make($file)->encode('webp', 80)->save($path . $name);
        return '/images/team/' . $name;
    }
}