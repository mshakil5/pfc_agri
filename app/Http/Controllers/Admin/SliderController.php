<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Cache;
use DataTables;
use Stichoza\GoogleTranslate\GoogleTranslate;

class SliderController extends Controller
{
    public function getSlider(Request $request)
    {
        if ($request->ajax()) {
            $sliders = Slider::with('translations')->orderBy('serial');

            return DataTables::of($sliders)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return $row->translateOrNew(app()->getLocale())->title;
                })
                ->addColumn('image', function ($row) {
                    return $row->image
                        ? '<img src="' . asset('images/slider/' . $row->image) . '" class="img-thumbnail" style="width:50px;height:50px;">'
                        : '';
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status == 1 ? 'checked' : '';
                    return '<div class="form-check form-switch" dir="ltr">
                                <input type="checkbox" class="form-check-input toggle-status"
                                       id="customSwitchStatus' . $row->id . '" data-id="' . $row->id . '" ' . $checked . '>
                                <label class="form-check-label" for="customSwitchStatus' . $row->id . '"></label>
                            </div>';
                })
                ->addColumn('serial', function ($row) {
                    return '<span class="serial-text">' . $row->serial . '</span>';
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
                                        data-delete-url="' . route('slider.delete', $row->id) . '"
                                        data-method="DELETE"
                                        data-table="#sliderTable">
                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                    </button>
                                </li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['image', 'status', 'serial', 'action'])
                ->filterColumn('title', function ($query, $keyword) {
                    $query->whereHas('translations', function ($q) use ($keyword) {
                        $q->where('title', 'like', "%{$keyword}%");
                    });
                })
                ->make(true);
        }

        $sliders = Slider::with('translations')->orderBy('serial')->get();
        return view('admin.slider.index', compact('sliders'));
    }

    public function sliderStore(Request $request)
    {
        $id = $request->codeid;

        $rules = [
            'image' => $id ? 'nullable|image' : 'required|image',
            'link'  => 'nullable|string',
            'title' => 'required|string',
            'sub_title' => 'nullable|string',
            'hero_badge' => 'nullable|string',
        ];

        $request->validate($rules);

        $data = Slider::findOrNew($id);
        $data->link = $request->link;

        if (!$data->exists) {
            $lastSerial = Slider::max('serial');
            $data->serial = $lastSerial ? $lastSerial + 1 : 1;
            $data->created_by = auth()->id();
        } else {
            $data->updated_by = auth()->id();
        }

        if ($request->hasFile('image')) {
            if ($data->image && file_exists(public_path('images/slider/' . $data->image))) {
                unlink(public_path('images/slider/' . $data->image));
            }

            $file = $request->file('image');
            $name = mt_rand(10000000, 99999999) . '.webp';
            $path = public_path('images/slider/');
            if (!file_exists($path)) mkdir($path, 0755, true);

            \Image::make($file)
                ->resize(1200, null, fn($c) => $c->aspectRatio())
                ->encode('webp', 50)
                ->save($path . $name);

            $data->image = $name;
        }

        $data->save();

        // English inputs
        $enTitle = $request->title;
        $enSubTitle = $request->sub_title ?? '';
        $enHeroBadge = $request->hero_badge ?? '';
        $enButtons = $request->input('buttons', []);
        $enStatCards = $request->input('stat_card', []);

        // Get other locales (exclude English)
        $otherLocales = array_diff(config('translatable.locales'), ['en']);

        // Translate and save for each locale
        foreach (config('translatable.locales') as $locale) {
            $translation = $data->translateOrNew($locale);

            if ($locale === 'en') {
                // Save English directly
                $translation->title = $enTitle;
                $translation->sub_title = $enSubTitle;
                $translation->hero_badge = $enHeroBadge;
                $translation->buttons = $enButtons;
                $translation->stat_card = $enStatCards;
            } else {
                // Translate to other language
                try {
                    $tr = new GoogleTranslate($locale);
                    $tr->setSource('en');

                    $translation->title = $tr->translate($enTitle);
                    usleep(200000);

                    $translation->sub_title = !empty($enSubTitle) ? $tr->translate($enSubTitle) : '';
                    usleep(200000);

                    $translation->hero_badge = !empty($enHeroBadge) ? $tr->translate($enHeroBadge) : '';
                    usleep(200000);

                    // Translate button labels only (keep links as-is)
                    $translatedButtons = [];
                    foreach ($enButtons as $btn) {
                        $translatedButtons[] = [
                            'label' => !empty($btn['label']) ? $tr->translate($btn['label']) : '',
                            'link' => $btn['link'] ?? ''
                        ];
                        usleep(200000);
                    }
                    $translation->buttons = $translatedButtons;

                    // Translate stat card titles only (keep values as-is)
                    $translatedStatCards = [];
                    foreach ($enStatCards as $sc) {
                        $translatedStatCards[] = [
                            'value' => $sc['value'] ?? '',
                            'title' => !empty($sc['title']) ? $tr->translate($sc['title']) : ''
                        ];
                        usleep(200000);
                    }
                    $translation->stat_card = $translatedStatCards;

                } catch (\Exception $e) {
                    // If translation fails, save empty or fallback
                    $translation->title = $enTitle;
                    $translation->sub_title = $enSubTitle;
                    $translation->hero_badge = $enHeroBadge;
                    $translation->buttons = $enButtons;
                    $translation->stat_card = $enStatCards;
                }
            }

            $translation->save();
        }

        Cache::forget('active_sliders');

        $message = $id ? 'Slider updated successfully!' : 'Slider created successfully!';
        return response()->json(['message' => $message], 200);
    }

    public function sliderEdit($id)
    {
        $slider = Slider::with('translations')->findOrFail($id);
        
        // Return only English translation data for editing
        $enTranslation = $slider->translate('en');
        
        return response()->json([
            'id' => $slider->id,
            'link' => $slider->link,
            'image' => $slider->image,
            'title' => $enTranslation->title,
            'sub_title' => $enTranslation->sub_title,
            'hero_badge' => $enTranslation->hero_badge,
            'buttons' => $enTranslation->buttons,
            'stat_card' => $enTranslation->stat_card,
        ]);
    }

    public function sliderDelete($id)
    {
        $slider = Slider::findOrFail($id);
        if ($slider->image && file_exists(public_path('images/slider/' . $slider->image))) {
            @unlink(public_path('images/slider/' . $slider->image));
        }
        $slider->delete();
        Cache::forget('active_sliders');
        return response()->json(['message' => 'Slider deleted successfully.'], 200);
    }

    public function toggleStatus(Request $request)
    {
        $slider = Slider::findOrFail($request->slider_id);
        $slider->status = $request->status;
        $slider->save();
        Cache::forget('active_sliders');
        return response()->json(['message' => 'Slider status updated successfully.'], 200);
    }

    public function updateOrder(Request $request)
    {
        foreach ($request->order as $index => $id) {
            Slider::where('id', $id)->update(['serial' => $index + 1]);
        }
        Cache::forget('active_sliders');
        return response()->json(['success' => true, 'message' => 'Slider order updated successfully!']);
    }
}