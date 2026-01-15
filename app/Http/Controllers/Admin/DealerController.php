<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Dealer;
use Illuminate\View\View;
use DataTables;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Cache;

class DealerController extends Controller
{


    public function getDealer(Request $request)
    {
        if ($request->ajax()) {
            // Use with() if you have relationships, otherwise keep it simple
            $dealers = Dealer::orderBy('id', 'desc')->get();
            
            return DataTables::of($dealers)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $checked = ($row->is_active == 1 || $row->status == 1) ? 'checked' : '';
                    return '<div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input toggle-status" 
                                    id="customSwitchStatus'.$row->id.'" data-id="'.$row->id.'" '.$checked.'>
                            </div>';
                })
                ->addColumn('action', function($row){
                    return '
                        <div class="dropdown text-center">
                            <button class="btn btn-soft-secondary btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="ri-more-fill"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button class="dropdown-item" id="EditBtn" rid="'.$row->id.'"><i class="ri-pencil-fill me-2 text-muted"></i> Edit</button></li>
                                <li class="dropdown-divider"></li>
                                <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('dealer.delete', $row->id).'" data-table="#dealerTable"><i class="ri-delete-bin-fill me-2 text-muted"></i> Delete</button></li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        // This part only runs on the first page load
        $productCats = Category::where('status', 1)->latest()->get();
        return view('admin.dealers.index', compact('productCats'));
    }

    public function store(Request $request)
    {
        $id = $request->codeid;
        $request->validate([
            'name'         => 'required|max:255',
            'region'       => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:20',
            'website_url'  => 'nullable|url',
            // Latitude: -90 to 90
            'lat'          => 'nullable|numeric|between:-90,90',
            // Longitude: -180 to 180
            'lng'          => 'nullable|numeric|between:-180,180',
            'services'     => 'nullable|array',
            'services.*'   => 'exists:categories,id', // Ensures selected IDs exist in categories table
        ]);
        $data = Dealer::findOrNew($id);
        $data->name = $request->name;
        $data->region = $request->region;
        $data->phone = $request->phone;
        $data->website_url = $request->website_url;
        $data->lat = $request->lat;
        $data->lng = $request->lng;
        
        // Ensure the Dealer Model has: protected $casts = ['services' => 'array'];
        $data->services = $request->services; 
        
        $data->save();
        
        return response()->json(['message' => $id ? 'Dealer updated!' : 'Dealer created!'], 200);
    }

    public function edit($id)
    {
        $slider = Dealer::findOrFail($id);
        return response()->json($slider);
    }

    public function delete($id)
    {
        $slider = Dealer::findOrFail($id);
        $slider->delete();
        return response()->json(['message'=>'Slider deleted successfully.'],200);
    }

    public function toggleStatus(Request $request)
    {
        $data = Dealer::findOrFail($request->id);
        $data->status = $request->status;
        $data->save();
        return response()->json(['message'=>'Status updated successfully.'],200);
    }



}
