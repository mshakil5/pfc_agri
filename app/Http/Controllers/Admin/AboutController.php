<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Intervention\Image\Facades\Image;

class AboutController extends Controller
{
    
    public function index(Request $request)
    {
        $about = About::where('pages','about')->first();
        return view('admin.about.index', compact('about'));
    }

    public function homepageAbout(Request $request)
    {
        $about = About::where('pages','homepage')->first();
        return view('admin.about.index', compact('about'));
    }

    public function store(Request $request)
    {
        $about = About::find($request->codeid);

        if (!$about) {
            return response()->json(['message' => 'Record not found!'], 404);
        }

        $about->title = $request->title;
        $about->sub_title = $request->sub_title;
        $about->header_title = $request->header_title;
        $about->header_subtitle = $request->header_subtitle;
        $about->year = $request->year;

        if ($request->hasFile('image')) {
            if ($about->image && file_exists(public_path('images/about/' . $about->image))) {
                unlink(public_path('images/about/' . $about->image));
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('images/about'), $filename);
            
            $about->image = $filename;
        }

        if ($request->has('features')) {
            $about->amenities = json_encode(array_values($request->features));
        } else {
            $about->amenities = json_encode([]); 
        }

        if ($about->save()) {
            return response()->json([
                'status' => 200,
                'message' => 'About page updated successfully!'
            ]);
        }

        return response()->json(['message' => 'Failed to update data'], 500);
    }


    
}
