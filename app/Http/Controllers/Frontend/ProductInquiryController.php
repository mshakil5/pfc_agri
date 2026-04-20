<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ProductInquiryMail;
use App\Models\ProductInquiry;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ContactEmail;
use Illuminate\Support\Facades\Mail;

class ProductInquiryController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'nullable|string|max:20',
            'message'    => 'required|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $productName = $product->translateOrNew('en')->title ?? 'Unknown Product';

        // 1. Save to Database
        $inquiry = ProductInquiry::create([
            'product_id' => $request->product_id,
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'message'    => $request->message,
        ]);

        // 2. Send Email to multiple admins from ContactEmails table
        try {
            $contactEmails = ContactEmail::where('status', 1)->pluck('email');
            
            if ($contactEmails->isNotEmpty()) {
                foreach ($contactEmails as $email) {
                    Mail::to($email)->send(new ProductInquiryMail($inquiry, $productName));
                }
            } else {
                // Fallback to default system email if no active contacts found
                Mail::to(config('mail.from.address'))
                    ->send(new ProductInquiryMail($inquiry, $productName));
            }
        } catch (\Exception $e) {
            // Log error but still return success to user
            \Log::error('Inquiry email failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Your inquiry has been submitted successfully! We will contact you soon.'
        ]);
    }
}