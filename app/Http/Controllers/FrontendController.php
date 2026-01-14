<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactEmail;
use App\Mail\ContactMail;
use App\Models\About;
use App\Models\Category;
use App\Models\Master;
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
        // dd($categories);
        return view('frontend.index', compact('slider','categories'));
    }

    public function aboutUs()
    {
        $data = About::where('pages', 'about')->first();
        // dd($data);
        if ($data) {
            $data->amenities = json_decode($data->amenities, true);
        }

        return view('frontend.about', compact('data'));
    }



    public function rAndD()
    {
        return view('frontend.rAndD');
    }

    public function inquire()
    {
        return view('frontend.inquire');
    }

    public function storeContact(Request $request)
    {

        
        try {

            $request->validate([
                'first_name' => 'required|string|min:2|max:50',
                'last_name'  => 'required|string|min:2|max:50',
                'email' => 'required|email|max:50',
                'preferred_date' => 'required|date',
                'dob' => 'nullable|date|after_or_equal:' . now()->subYears(5)->format('Y-m-d'),
                'phone' => ['required'],
                'subject' => 'nullable|string|max:255',
                'message' => 'nullable|string|max:2000',
            ]);

            $contact = new Contact();
            $contact->first_name = $request->input('first_name');
            $contact->last_name  = $request->input('last_name');
            $contact->email      = $request->input('email');
            $contact->phone      = $request->input('phone');
            $contact->subject    = $request->input('subject');
            $contact->dob        = $request->input('dob');
            $contact->preferred_date        = $request->input('preferred_date');
            $contact->message    = $request->input('message');
            $contact->pref_time  = $request->input('prefTime');
            $contact->nursery    = $request->input('nursery');
            $contact->save();

            $contactEmails = ContactEmail::where('status', 1)->pluck('email');

            foreach ($contactEmails as $contactEmail) {
                Mail::to($contactEmail)->send(new ContactMail($contact));
            }


            return redirect()->to(url()->previous() . '#callback')
                            ->with('success', 'Your message has been sent successfully!');
            
        } catch (ValidationException $e) {
            throw ValidationException::withMessages($e->errors())
                ->redirectTo(url()->previous() . '#callback');
        }

    }





}
