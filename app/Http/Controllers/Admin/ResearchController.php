<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Master;
use Illuminate\Http\Request;

class ResearchController extends Controller
{
    public function research (Request $request)
    {
        $data = Master::where('pages', 'rnd')->first();
        // dd($data);
        return view('admin.research.index',compact('data'));
    }

    public function researchUpdate(Request $request)
    {
        $data = Master::find($request->codeid);

        if (!$data) {
            return response()->json(['message' => 'Record not found!'], 404);
        }

        $data->short_title = $request->short_title;
        $data->long_title = $request->long_title;
        $data->name = $request->name;
        $data->meta_title = $request->meta_title;
        $data->meta_description = $request->meta_description;
        $data->meta_keywords = $request->meta_keywords;

        if ($request->hasFile('meta_image')) {
            if ($data->meta_image && file_exists(public_path('images/meta/' . $data->meta_image))) {
                unlink(public_path('images/meta/' . $data->meta_image));
            }

            $file = $request->file('meta_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('images/meta'), $filename);
            
            $data->meta_image = $filename;
        }

        if ($request->has('features')) {
            $data->extra1 = json_encode(array_values($request->features));
        } else {
            $data->extra1 = json_encode([]); 
        }

        if ($data->save()) {
            return response()->json([
                'status' => 200,
                'message' => 'Data updated successfully!'
            ]);
        }

        return response()->json(['message' => 'Failed to update data'], 500);
    }

}
