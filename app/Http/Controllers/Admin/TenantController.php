<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Property;
use DataTables;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tenants = Tenant::with('property')->select([
                'id', 
                'property_id', 
                'name', 
                'email', 
                'phone', 
                'reference_checked', 
                'immigration_status',
                'right_to_rent_status',
                'status'
            ])->orderBy('id', 'desc');
            
            return DataTables::of($tenants)
                ->addIndexColumn()
                ->addColumn('property_name', function ($row) {
                    return $row->property ? $row->property->property_name : '<span class="text-muted">N/A</span>';
                })
                ->addColumn('reference_checked', function ($row) {
                    $badge_class = [
                        'Yes' => 'bg-success',
                        'No' => 'bg-danger', 
                        'Processing' => 'bg-warning'
                    ][$row->reference_checked] ?? 'bg-secondary';
                    
                    return '<span class="badge '.$badge_class.'">'.$row->reference_checked.'</span>';
                })
                ->addColumn('immigration_status', function ($row) {
                    $badge_class = [
                        'Checked' => 'bg-success',
                        'Pending' => 'bg-warning', 
                        'Not Checked' => 'bg-danger'
                    ][$row->immigration_status] ?? 'bg-secondary';
                    
                    return '<span class="badge '.$badge_class.'">'.$row->immigration_status.'</span>';
                })
                ->addColumn('right_to_rent', function ($row) {
                    $badge_class = [
                        'Verified' => 'bg-success',
                        'Pending' => 'bg-warning', 
                        'Not Verified' => 'bg-danger'
                    ][$row->right_to_rent_status] ?? 'bg-secondary';
                    
                    return '<span class="badge '.$badge_class.'">'.$row->right_to_rent_status.'</span>';
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status == 1 ? 'checked' : '';
                    return '<div class="form-check form-switch" dir="ltr">
                                <input type="checkbox" class="form-check-input toggle-status" 
                                      id="customSwitchStatus'.$row->id.'" data-id="'.$row->id.'" '.$checked.'>
                                <label class="form-check-label" for="customSwitchStatus'.$row->id.'"></label>
                            </div>';
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
                                    <button class="dropdown-item" id="EditBtn" rid="'.$row->id.'">
                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit
                                    </button>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <button class="dropdown-item deleteBtn" 
                                            data-delete-url="' . route('tenant.delete', $row->id) . '" 
                                            data-method="DELETE" 
                                            data-table="#tenantTable">
                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                    </button>
                                </li>
                            </ul>
                        </div>
                    ';
                })
                ->rawColumns(['property_name', 'reference_checked', 'immigration_status', 'right_to_rent', 'status', 'action'])
                ->make(true);
        }

        $properties = Property::where('status', 'Vacant')->orWhere('status', 'Occupied')->get();
        return view('admin.tenant.index', compact('properties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'name' => 'required',
            'email' => 'required|email|unique:tenants,email',
            'phone' => 'required',
            'address' => 'nullable',
            'current_address' => 'required',
            'previous_address' => 'nullable',
            'bank_name' => 'nullable',
            'account_number' => 'nullable',
            'sort_code' => 'nullable',
            'emergency_contact_name' => 'nullable',
            'emergency_contact_phone' => 'nullable',
            'emergency_contact_relation' => 'nullable',
            'reference_checked' => 'required|in:Yes,No,Processing',
            'previous_landlord_reference' => 'nullable',
            'personal_reference' => 'nullable',
            'credit_score' => 'nullable',
            'immigration_status' => 'required|in:Checked,Pending,Not Checked',
            'right_to_rent_status' => 'required|in:Verified,Not Verified,Pending',
            'right_to_rent_check_date' => 'nullable|date'
        ]);

        $data = new Tenant;
        $data->property_id = $request->property_id;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->current_address = $request->current_address;
        $data->previous_address = $request->previous_address;
        $data->bank_name = $request->bank_name;
        $data->account_number = $request->account_number;
        $data->sort_code = $request->sort_code;
        $data->emergency_contact_name = $request->emergency_contact_name;
        $data->emergency_contact_phone = $request->emergency_contact_phone;
        $data->emergency_contact_relation = $request->emergency_contact_relation;
        $data->reference_checked = $request->reference_checked;
        $data->previous_landlord_reference = $request->previous_landlord_reference;
        $data->personal_reference = $request->personal_reference;
        $data->credit_score = $request->credit_score;
        $data->immigration_status = $request->immigration_status;
        $data->right_to_rent_status = $request->right_to_rent_status;
        $data->right_to_rent_check_date = $request->right_to_rent_check_date;
        
        if ($data->save()) {
            // Update property status to Occupied
            Property::where('id', $request->property_id)->update(['status' => 'Occupied']);
            
            return response()->json([
                'message' => 'Tenant created successfully!',
                'tenant' => $data 
            ], 200);
        }

        return response()->json([
            'message' => 'Server error while creating tenant.'
        ], 500);
    }

    public function edit($id)
    {
        $where = [
            'id'=>$id
        ];
        $info = Tenant::where($where)->get()->first();
        return response()->json($info);
    }

    public function update(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'name' => 'required',
            'email' => 'required|email|unique:tenants,email,' . $request->codeid,
            'phone' => 'required',
            'address' => 'nullable',
            'current_address' => 'required',
            'previous_address' => 'nullable',
            'bank_name' => 'nullable',
            'account_number' => 'nullable',
            'sort_code' => 'nullable',
            'emergency_contact_name' => 'nullable',
            'emergency_contact_phone' => 'nullable',
            'emergency_contact_relation' => 'nullable',
            'reference_checked' => 'required|in:Yes,No,Processing',
            'previous_landlord_reference' => 'nullable',
            'personal_reference' => 'nullable',
            'credit_score' => 'nullable',
            'immigration_status' => 'required|in:Checked,Pending,Not Checked',
            'right_to_rent_status' => 'required|in:Verified,Not Verified,Pending',
            'right_to_rent_check_date' => 'nullable|date'
        ]);

        $data = Tenant::findOrFail($request->codeid);
        $data->property_id = $request->property_id;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->current_address = $request->current_address;
        $data->previous_address = $request->previous_address;
        $data->bank_name = $request->bank_name;
        $data->account_number = $request->account_number;
        $data->sort_code = $request->sort_code;
        $data->emergency_contact_name = $request->emergency_contact_name;
        $data->emergency_contact_phone = $request->emergency_contact_phone;
        $data->emergency_contact_relation = $request->emergency_contact_relation;
        $data->reference_checked = $request->reference_checked;
        $data->previous_landlord_reference = $request->previous_landlord_reference;
        $data->personal_reference = $request->personal_reference;
        $data->credit_score = $request->credit_score;
        $data->immigration_status = $request->immigration_status;
        $data->right_to_rent_status = $request->right_to_rent_status;
        $data->right_to_rent_check_date = $request->right_to_rent_check_date;

        if ($data->save()) {
            return response()->json([
                'message' => 'Tenant updated successfully!'
            ], 200);
        }

        return response()->json([
            'message' => 'Failed to update tenant. Please try again.'
        ], 500);
    }

    public function delete($id)
    {
        $data = Tenant::find($id);
        
        if (!$data) {
            return response()->json([
                'message' => 'Tenant not found.'
            ], 404);
        }

        // Update property status to Vacant when tenant is deleted
        Property::where('id', $data->property_id)->update(['status' => 'Vacant']);

        if ($data->delete()) {
            return response()->json([
                'message' => 'Tenant deleted successfully.'
            ], 200);
        }

        return response()->json([
            'message' => 'Failed to delete tenant.'
        ], 500);
    }

    public function toggleStatus(Request $request)
    {
        $tenant = Tenant::find($request->tenant_id);

        if (!$tenant) {
            return response()->json([
                'message' => 'Tenant not found'
            ], 404);
        }

        $tenant->status = $request->status;

        if ($tenant->save()) {
            return response()->json([
                'message' => 'Tenant status updated successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'Failed to update tenant status'
        ], 500);
    }
}