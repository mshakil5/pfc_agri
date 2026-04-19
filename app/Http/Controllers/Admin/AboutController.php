<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Stichoza\GoogleTranslate\GoogleTranslate;

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
        $about->long_description = $request->long_description;

        // Only update header fields if it's the main 'about' page
        if ($about->pages == 'about') {
            $about->header_title     = $request->header_title;
            $about->header_subtitle  = $request->header_subtitle;
            $about->year             = $request->year;
        }

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

        // Save English amenities in main column
        $enAmenities = array_values(array_filter(
            $request->input('features', []),
            fn($a) => !empty($a['title'])
        ));
        $about->amenities = json_encode($enAmenities);

        // Translate and build JSON for other languages
        $translations = $about->translations ?? [];
        $otherLocales = array_diff(config('translatable.locales'), ['en']);

        foreach ($otherLocales as $locale) {
            try {
                $tr = new GoogleTranslate($locale);
                $tr->setSource('en');

                $translations[$locale]['title']            = $tr->translate($request->title);
                usleep(200000);

                $translations[$locale]['sub_title']        = !empty($request->sub_title) ? $tr->translate($request->sub_title) : '';
                usleep(200000);

                $translations[$locale]['long_description'] = !empty($request->long_description) ? $tr->translate($request->long_description) : '';
                usleep(300000); // Longer delay for HTML content

                // Translate amenities (Keep icon classes the same as English)
                $translatedAmenities = [];
                foreach ($enAmenities as $amenity) {
                    $translatedAmenities[] = [
                        'icon'     => $amenity['icon'] ?? '',
                        'title'    => $tr->translate($amenity['title']),
                        'subtitle' => !empty($amenity['subtitle']) ? $tr->translate($amenity['subtitle']) : '',
                    ];
                    usleep(200000);
                }
                $translations[$locale]['amenities'] = $translatedAmenities;

                if ($about->pages == 'about') {
                    $translations[$locale]['header_title']     = $tr->translate($request->header_title);
                    usleep(200000);
                    $translations[$locale]['header_subtitle']  = !empty($request->header_subtitle) ? $tr->translate($request->header_subtitle) : '';
                    usleep(200000);
                }

            } catch (\Exception $e) {
                // Fallback to English if translation fails
                $translations[$locale] = [
                    'title'            => $request->title,
                    'sub_title'        => $request->sub_title,
                    'long_description' => $request->long_description,
                    'amenities'        => $enAmenities,
                ];
                if ($about->pages == 'about') {
                    $translations[$locale]['header_title']     = $request->header_title;
                    $translations[$locale]['header_subtitle']  = $request->header_subtitle;
                }
            }
        }
        
        $about->translations = $translations;

        if ($about->save()) {
            return response()->json(['status' => 200, 'message' => 'About page updated & translated successfully!']);
        }

        return response()->json(['message' => 'Failed to update data'], 500);
    }
}