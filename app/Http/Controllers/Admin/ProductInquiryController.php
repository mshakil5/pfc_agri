<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductInquiry;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Str;

class ProductInquiryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $inquiries = ProductInquiry::with(['product.translations', 'product.category.translations'])
                ->latest();

            return DataTables::of($inquiries)
                ->addIndexColumn()
                ->addColumn('product', function ($row) {
                    if ($row->product) {
                        $title = $row->product->translateOrNew(app()->getLocale())->title;
                        $cat = $row->product->category ? $row->product->category->translateOrNew(app()->getLocale())->name : 'N/A';
                        return '<strong>' . $title . '</strong><br><small class="text-muted">' . $cat . '</small>';
                    }
                    return '<span class="text-danger">Deleted Product</span>';
                })
                ->addColumn('customer', function ($row) {
                    $contact = $row->name . '<br><small class="text-muted">' . $row->email . '</small>';
                    if ($row->phone) {
                        $contact .= '<br><small class="text-muted">' . $row->phone . '</small>';
                    }
                    return $contact;
                })
                ->addColumn('message', function ($row) {
                    return '<span title="' . htmlspecialchars($row->message) . '">' . Str::limit($row->message, 80) . '...</span>';
                })
                ->addColumn('date', function ($row) {
                    return $row->created_at->format('d M Y h:i A');
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="dropdown">
                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown"><i class="ri-eye-line align-middle"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                
                                <li class="dropdown-divider"></li>
                                <li>
                                    <button class="dropdown-item deleteBtn"
                                        data-delete-url="'.route('admin.inquiries.destroy', $row->id).'"
                                        data-table="#inquiryTable">
                                        <i class="ri-delete-bin-line align-bottom me-2 text-muted"></i> Delete
                                    </button>
                                </li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['product', 'customer', 'message', 'action'])
                ->make(true);
        }

        return view('admin.inquiries.index');
    }

    public function show($id)
    {
        $inquiry = ProductInquiry::with(['product.translations', 'product.category.translations'])->findOrFail($id);
        
        $inquiry->update(['is_read' => 1]); // Mark as read

        return response()->json([
            'id' => $inquiry->id,
            'name' => $inquiry->name,
            'email' => $inquiry->email,
            'phone' => $inquiry->phone,
            'message' => $inquiry->message,
            'date' => $inquiry->created_at->format('d M Y h:i A'),
            'product' => $inquiry->product ? [
                'title' => $inquiry->product->translateOrNew(app()->getLocale())->title,
                'category' => $inquiry->product->category ? $inquiry->product->category->translateOrNew(app()->getLocale())->name : 'N/A',
                'image' => $inquiry->product->image
            ] : null
        ]);
    }

    public function destroy($id)
    {
        ProductInquiry::destroy($id);
        return response()->json(['message' => 'Inquiry deleted successfully.']);
    }
}