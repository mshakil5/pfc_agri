<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactEmail;
use App\Mail\ContactMail;
use App\Models\About;
use App\Models\Award;
use App\Models\Blog;
use App\Models\Category;
use App\Models\CompanyDetails;
use App\Models\Dealer;
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
        $sliders = Slider::orderBy('serial')->where('status', 1)->get();
        $categories = Category::with('products')->where('status', 1)->limit(6)->get();
        $about = About::where('pages','homepage')->first();

        $company = CompanyDetails::select('company_name', 'fav_icon', 'google_site_verification', 'footer_content', 'facebook', 'twitter', 'linkedin', 'website', 'phone1', 'email1', 'address1','address2','company_logo','copyright','google_map')->first();

        $awards = Award::with('translations')->latest('year')->get();

        $blogs = Blog::with('translations')
                ->where('status', 1)
                ->latest('published_at')
                ->take(3)
                ->get();

        $dealers = Dealer::where('status', 1)->orderBy('id', 'desc')->get();

        return view('frontend.index', compact('sliders','categories','about','company','awards','blogs','dealers'));
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

    public function contact()
    {
        $categories = Category::with('products')->where('status', 1)->get();
        return view('frontend.inquire', compact('categories'));
    }

    public function storeContact(Request $request)
    {
        try {
            // 1. Validation matching translated form fields
            $request->validate([
                'full_name'   => 'required|string|min:2|max:100',
                'email'       => 'required|email|max:100',
                'phone'       => 'nullable|string|max:20',
                'subject'     => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'message'     => 'required|string|min:10|max:3000',
            ], [
                'full_name.required' => __('Full Name is required'),
                'full_name.min'      => __('Full Name must be at least :min characters'),
                'email.required'     => __('Email Address is required'),
                'email.email'        => __('Enter a valid Email Address'),
                'message.required'   => __('Your Message is required'),
                'message.min'        => __('Your Message must be at least :min characters'),
            ]);

            // 2. Save contact
            $contact = new Contact();
            $names = explode(' ', $request->input('full_name'), 2);
            $contact->first_name = $names[0];
            $contact->last_name  = $names[1] ?? '';

            $contact->full_name   = $request->input('full_name');
            $contact->email       = $request->input('email');
            $contact->phone       = $request->input('phone');
            $contact->subject     = $request->input('subject');
            $contact->category_id = $request->input('category_id');
            $contact->message     = $request->input('message');

            $contact->save();

            // 3. Email Notification
            $contactEmails = ContactEmail::where('status', 1)->pluck('email');
            foreach ($contactEmails as $email) {
                Mail::to($email)->send(new ContactMail($contact));
            }

            return redirect()->back()->with('success', __('Your message has been sent successfully!'));

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }


    public function blogDetails($slug)
    {
        // Find the blog post that matches the slug in any language
        $blog = \App\Models\Blog::whereTranslation('slug', $slug)->firstOrFail();
        
        // Optional: Get related posts (e.g., latest 3 excluding current)
        $relatedPosts = \App\Models\Blog::where('id', '!=', $blog->id)
                            ->where('status', 1)
                            ->latest()
                            ->take(3)
                            ->get();

        return view('frontend.blog-detail', compact('blog', 'relatedPosts'));
    }

    public function blogList()
    {
        $blogs = \App\Models\Blog::where('status', 1)
                    ->latest('published_at')
                    ->paginate(9); // 9 = 3 per row × 3 rows

        return view('frontend.blog-list', compact('blogs'));
    }





}
