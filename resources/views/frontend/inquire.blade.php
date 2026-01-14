@extends('frontend.layouts.master')

@section('content')

    @php
        $company = App\Models\CompanyDetails::select('company_name', 'fav_icon', 'google_site_verification', 'footer_content', 'facebook', 'twitter', 'linkedin', 'website', 'phone1', 'email1', 'address1','address2','company_logo','copyright','google_map')->first();
    @endphp

    <style>
        /* --- Contact Hero Header --- */
        .contact-hero {
            background-color: #00a651; /* Brand Green */
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        /* --- Contact Section Layout --- */
        .contact-container {
            padding: 80px 0;
            background-color: #f8fafc;
        }

        .contact-info-card, .contact-form-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: none;
        }

        /* Sidebar Info Styles */
        .info-item {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .info-icon-box {
            width: 45px;
            height: 45px;
            background-color: #f0fdf4;
            color: #00a651;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        /* Form Styles */
        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 8px;
        }

        .form-label span {
            color: #00a651;
        }

        .form-control, .form-select {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #00a651;
            box-shadow: 0 0 0 3px rgba(0, 166, 81, 0.1);
        }

        .btn-send {
            background-color: #00a651;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }

        .btn-send:hover {
            background-color: #008d44;
            transform: translateY(-2px);
        }
    </style>


<section class="contact-hero">
    <div class="container">
        <h1 class="fw-bold display-5 mb-3">Get In Touch</h1>
        <p class="opacity-90 mx-auto" style="max-width: 600px;">
            Have a question or need a quote? We'd love to hear from you. Fill out the form below and our team will respond promptly.
        </p>
    </div>
</section>

<section class="contact-container">
    <div class="container">
        <div class="row g-4">
            
            <div class="col-lg-4">
                <div class="contact-info-card h-100">
                    <h4 class="fw-bold mb-4" style="color: #00a651;">Contact Information</h4>
                    
                    <div class="info-item">
                        <div class="info-icon-box"><i class="fas fa-phone"></i></div>
                        <div>
                            <p class="fw-bold mb-0">Phone</p>
                            <p class="text-muted small mb-0">{{ $company->phone1 }}</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon-box"><i class="fas fa-envelope"></i></div>
                        <div>
                            <p class="fw-bold mb-0">Email</p>
                            <p class="text-muted small mb-0">{{ $company->email1 }}</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon-box"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <p class="fw-bold mb-0">Address</p>
                            <p class="text-muted small mb-0">
                                {!! $company->address1 !!}
                                
                                
                            </p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon-box"><i class="fas fa-clock"></i></div>
                        <div>
                            <p class="fw-bold mb-0">Business Hours</p>
                            <p class="text-muted small mb-0">
                                {!! $company->address2 !!}
                            </p>
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">
                    <p class="small text-muted mb-0">For urgent inquiries outside business hours, please call our emergency line.</p>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="contact-form-card">
                    <h4 class="fw-bold mb-2" style="color: #00a651;">Send us a Message</h4>
                    <p class="text-muted small mb-4">Fill out the form below and we'll get back to you as soon as possible.</p>
                    
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span>*</span></label>
                                <input type="text" class="form-control" placeholder="John Smith" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address <span>*</span></label>
                                <input type="email" class="form-control" placeholder="john@example.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" placeholder="+44 1234 567890">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subject</label>
                                <select class="form-select">
                                    <option selected disabled>Select a subject</option>
                                    <option>Product Inquiry</option>
                                    <option>Technical Support</option>
                                    <option>Partnership</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Product Interest</label>
                                <select class="form-select">
                                    <option selected disabled>Select a product category (optional)</option>
                                    <option>Slurry Management</option>
                                    <option>Wet Bale Management</option>
                                    <option>Field Preparation</option>
                                    <option>Woodland Management</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Your Message <span>*</span></label>
                                <textarea class="form-control" rows="5" placeholder="Please describe your inquiry in detail..." required></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn-send"><i class="fas fa-paper-plane me-2"></i> Send Message</button>
                                <span class="ms-3 text-muted small">* Required fields</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>




@endsection

@section('script')


@endsection