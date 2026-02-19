<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class AboutController extends Controller
{
    public function index(Request $request)
    {
        $about = About::where('pages', 'about')->first();
        return view('admin.about.index', compact('about'));
    }

    public function homepageAbout(Request $request)
    {
        $about = About::where('pages', 'homepage')->first();
        return view('admin.about.index', compact('about'));
    }

    public function store(Request $request)
    {
        $about = About::find($request->codeid);

        if (!$about) {
            return response()->json(['message' => 'Record not found!'], 404);
        }

        $about->title            = $request->title;
        $about->sub_title        = $request->sub_title;
        $about->header_title     = $request->header_title;
        $about->header_subtitle  = $request->header_subtitle;
        $about->long_description = $request->long_description;
        $about->year             = $request->year;

        if ($request->hasFile('image')) {
            if ($about->image && file_exists(public_path('images/about/' . $about->image))) {
                @unlink(public_path('images/about/' . $about->image));
            }

            $name = mt_rand(10000000, 99999999) . '.webp';
            $path = public_path('images/about/');
            if (!file_exists($path)) mkdir($path, 0755, true);

            Image::make($request->file('image'))
                ->resize(1200, null, fn($c) => $c->aspectRatio())
                ->encode('webp', 80)
                ->save($path . $name);

            $about->image = $name;
        }

        $about->amenities = json_encode(
            $request->has('features') ? array_values($request->features) : []
        );

        // Save translations
        $translations = $about->translations ?? [];
        foreach (config('translatable.locales') as $locale) {
            if ($locale === 'en') continue;
            $translations[$locale] = [
                'title'            => $request->input("trans.$locale.title"),
                'sub_title'        => $request->input("trans.$locale.sub_title"),
                'header_title'     => $request->input("trans.$locale.header_title"),
                'header_subtitle'  => $request->input("trans.$locale.header_subtitle"),
                'long_description' => $request->input("trans.$locale.long_description"),
                'amenities'        => array_values(array_filter(
                    $request->input("trans.$locale.amenities", []),
                    fn($a) => !empty($a['title'])
                )),
            ];
        }
        $about->translations = $translations;

        if ($about->save()) {
            return response()->json(['status' => 200, 'message' => 'About page updated successfully!']);
        }

        return response()->json(['message' => 'Failed to update data'], 500);
    }
}