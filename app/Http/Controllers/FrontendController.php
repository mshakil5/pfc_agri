<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactEmail;
use App\Mail\ContactMail;
use App\Models\About;
use App\Models\Category;
use App\Models\CompanyDetails;
use App\Models\Master;
use App\Models\Research;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class FrontendController extends Controller
{
    
    public function index()
    {
        $slider = Slider::orderby('id')->first();
        $categories = Category::with('products')->where('status', 1)->get();
        $about = About::where('pages','homepage')->first();
        // dd($about);

        $company = CompanyDetails::select('company_name', 'fav_icon', 'google_site_verification', 'footer_content', 'facebook', 'twitter', 'linkedin', 'website', 'phone1', 'email1', 'address1','address2','company_logo','copyright','google_map')->first();


        return view('frontend.index', compact('slider','categories','about','company'));
    }

    public function aboutUs()
    {
        $data = About::where('pages', 'about')->first();
        if ($data) {
            $data->amenities = json_decode($data->amenities, true);
        }

        $company = CompanyDetails::select('company_name', 'fav_icon', 'google_site_verification', 'footer_content', 'facebook', 'twitter', 'linkedin', 'website', 'phone1', 'email1', 'address1','address2','company_logo','copyright','google_map')->first();

        return view('frontend.about', compact('data','company'));
    }



    public function rAndD()
    {
        $data = Research::orderby('id', 'DESC')->get();
        $research = Master::where('pages', 'rnd')->first();
        return view('frontend.rAndD', compact('data','research'));
    }

    public function inquire()
    {
        $categories = Category::with('products')->where('status', 1)->get();
        return view('frontend.inquire', compact('categories'));
    }

    public function storeContact(Request $request)
    {
        try {
            // 1. Validation matching new form names
            $request->validate([
                'full_name'   => 'required|string|min:2|max:100',
                'email'       => 'required|email|max:100',
                'phone'       => 'nullable|string|max:20',
                'subject'     => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id', 
                'message'     => 'required|string|min:10|max:3000',
            ]);

            $contact = new Contact();
            $names = explode(' ', $request->input('full_name'), 2);
            $contact->first_name = $names[0];
            $contact->last_name  = $names[1] ?? ''; 
            
            $contact->full_name       = $request->input('full_name');
            $contact->email       = $request->input('email');
            $contact->phone       = $request->input('phone');
            $contact->subject     = $request->input('subject');
            $contact->category_id = $request->input('category_id');
            $contact->message     = $request->input('message');
            
            $contact->save();

            // 3. Email Notification
            $contactEmails = ContactEmail::where('status', 1)->pluck('email');

            if ($contactEmails->count() > 0) {
                foreach ($contactEmails as $email) {
                    Mail::to($email)->send(new ContactMail($contact));
                }
            }

            return redirect()->back()->with('success', 'Your message has been sent successfully!');
            
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }





}
