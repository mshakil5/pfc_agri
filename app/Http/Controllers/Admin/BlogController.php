<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Stichoza\GoogleTranslate\GoogleTranslate;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $blogs = Blog::with('translations')->latest();
            return DataTables::of($blogs)
                ->addIndexColumn()
                ->addColumn('title', function($row) {
                    return $row->translateOrNew(app()->getLocale())->title;
                })
                ->addColumn('image', function($row) {
                    return $row->image ? '<img src="'.asset($row->image).'" width="50" class="img-thumbnail">' : 'No Image';
                })
                ->addColumn('action', function($row) {
                    return '
                        <div class="dropdown">
                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown"><i class="ri-more-fill"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button class="dropdown-item" id="EditBtn" rid="'.$row->id.'"><i class="ri-pencil-fill me-2 text-muted"></i> Edit</button></li>
                                <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('blogs.destroy', $row->id).'" data-table="#blogTable"><i class="ri-delete-bin-fill me-2 text-muted"></i> Delete</button></li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['image', 'action'])
                ->filterColumn('title', function ($query, $keyword) {
                    $query->whereHas('translations', function ($q) use ($keyword) {
                        $q->where('title', 'like', "%{$keyword}%");
                    });
                })
                ->make(true);
        }
        return view('admin.blog.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'author_name' => 'required|string',
            'published_at' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'title' => 'required|string',
            'excerpt' => 'required|string',
            'description' => 'required|string',
        ]);

        $blog = new Blog();
        $blog->author_name = $request->author_name;
        $blog->published_at = $request->published_at;

        if ($request->hasFile('image')) {
            $blog->image = $this->uploadImage($request->file('image'));
        }
        $blog->save();

        $this->saveTranslations($blog, $request);

        return response()->json(['message' => 'Blog created & translated successfully!']);
    }

    public function edit($id)
    {
        $blog = Blog::with('translations')->findOrFail($id);
        $enTranslation = $blog->translate('en');
        
        return response()->json([
            'id' => $blog->id,
            'author_name' => $blog->author_name,
            'published_at' => $blog->published_at,
            'image' => $blog->image,
            'title' => $enTranslation->title,
            'excerpt' => $enTranslation->excerpt,
            'description' => $enTranslation->description,
        ]);
    }

    public function update(Request $request)
    {
        $blog = Blog::findOrFail($request->codeid);
        
        $request->validate([
            'author_name' => 'required|string',
            'published_at' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'title' => 'required|string',
            'excerpt' => 'required|string',
            'description' => 'required|string',
        ]);

        $blog->author_name = $request->author_name;
        $blog->published_at = $request->published_at;

        if ($request->hasFile('image')) {
            if ($blog->image && File::exists(public_path($blog->image))) {
                File::delete(public_path($blog->image));
            }
            $blog->image = $this->uploadImage($request->file('image'));
        }
        $blog->save();

        $this->saveTranslations($blog, $request);

        return response()->json(['message' => 'Blog updated & translated successfully!']);
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->image && File::exists(public_path($blog->image))) {
            File::delete(public_path($blog->image));
        }
        $blog->delete();
        return response()->json(['message' => 'Blog deleted successfully!']);
    }

    private function uploadImage($file)
    {
        $imageName = time() . '_' . uniqid() . '.webp';
        $path = public_path('uploads/blogs');
        if (!file_exists($path)) mkdir($path, 0777, true);

        \Image::make($file)->encode('webp', 80)->save($path . '/' . $imageName);
        return 'uploads/blogs/' . $imageName;
    }

    private function saveTranslations($blog, $request)
    {
        $enTitle = $request->title;
        $enExcerpt = $request->excerpt;
        $enDescription = $request->description;

        $otherLocales = array_diff(config('translatable.locales'), ['en']);

        foreach (config('translatable.locales') as $locale) {
            $translation = $blog->translateOrNew($locale);

            if ($locale === 'en') {
                $translation->title = $enTitle;
                $translation->slug = Str::slug($enTitle);
                $translation->excerpt = $enExcerpt;
                $translation->description = $enDescription;
            } else {
                try {
                    $tr = new GoogleTranslate($locale);
                    $tr->setSource('en');

                    // Translate Title & generate slug
                    $transTitle = $tr->translate($enTitle);
                    $translation->title = $transTitle;
                    $translation->slug = Str::slug($transTitle);
                    usleep(200000);

                    // Translate Excerpt
                    $translation->excerpt = !empty($enExcerpt) ? $tr->translate($enExcerpt) : '';
                    usleep(200000);

                    // Translate Description (Longer delay for HTML content)
                    $translation->description = !empty($enDescription) ? $tr->translate($enDescription) : '';
                    usleep(300000); 

                } catch (\Exception $e) {
                    // Fallback to English if translation fails
                    $translation->title = $enTitle;
                    $translation->slug = Str::slug($enTitle);
                    $translation->excerpt = $enExcerpt;
                    $translation->description = $enDescription;
                }
            }
            $translation->save();
        }
    }
}