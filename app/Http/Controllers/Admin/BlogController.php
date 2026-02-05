<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Master;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{


    public function blog(Request $request)
    {
        if ($request->ajax()) {
            $data = Master::where('pages', 'blog')->orderByDesc('id');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="dropdown">
                            <button class="btn btn-soft-secondary btn-sm" data-bs-toggle="dropdown"><i class="ri-more-fill"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button class="dropdown-item EditBtn" data-id="'.$row->id.'"><i class="ri-pencil-fill me-2"></i>Edit</button></li>
                                <li class="dropdown-divider"></li>
                                <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('master.delete', $row->id).'"><i class="ri-delete-bin-fill me-2"></i>Delete</button></li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.blog.index');
    }


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
                    return $row->image ? '<img src="'.asset($row->image).'" width="50">' : 'No Image';
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
                ->make(true);
        }
        return view('admin.blog.index');
    }

    

public function store(Request $request)
{
    $data = $this->validateBlog($request);
    
    if ($request->hasFile('image')) {
        $data['image'] = $this->uploadImage($request->file('image'));
    }

    Blog::create($data);
    return response()->json(['message' => 'Blog created successfully!']);
}

public function update(Request $request)
{
    $blog = Blog::findOrFail($request->codeid);
    $data = $this->validateBlog($request);

    if ($request->hasFile('image')) {
        // Delete old image if it exists
        if ($blog->image && File::exists(public_path($blog->image))) {
            File::delete(public_path($blog->image));
        }
        $data['image'] = $this->uploadImage($request->file('image'));
    }

    $blog->update($data);
    return response()->json(['message' => 'Blog updated successfully!']);
}

// Helper method for uploading
private function uploadImage($file)
{
    $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    $file->move(public_path('uploads/blogs'), $imageName);
    return 'uploads/blogs/' . $imageName;
}



    public function destroy($id)
    {
        Blog::destroy($id);
        return response()->json(['message' => 'Blog deleted successfully!']);
    }

    private function validateBlog($request) {
        $rules = [
            'author_name' => 'required|string',
            'published_at' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validate as image
        ];

        foreach (config('translatable.locales') as $locale) {
            $rules["$locale.title"] = 'required|string';
            $rules["$locale.excerpt"] = 'required|string';
            $rules["$locale.description"] = 'required|string';
        }
        
        $validated = $request->validate($rules);

        // Auto-generate slugs for each locale
        foreach (config('translatable.locales') as $locale) {
            if (isset($validated[$locale]['title'])) {
                $validated[$locale]['slug'] = Str::slug($validated[$locale]['title']);
            }
        }
        return $validated;
    }


}
