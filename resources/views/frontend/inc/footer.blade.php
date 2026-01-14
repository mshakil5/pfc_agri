
@php
    $categories = \App\Models\Category::with('products')->where('status', 1)->get();
@endphp

<footer class="main-footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-3">
                <h4 class="fw-bold mb-4">PFC Agri Solutions</h4>
                <p class="small opacity-75 mb-4">{{ $company->footer_content }}</p>
                <div class="d-flex">
                    <a href="{{ $company->facebook }}" class="social-circle"><i class="fab fa-facebook-f"></i></a>
                    <a href="{{ $company->instagram }}" class="social-circle"><i class="fab fa-instagram"></i></a>
                    <a href="{{ $company->linkedin }}" class="social-circle"><i class="fab fa-linkedin-in"></i></a>
                    <a href="{{ $company->youtube }}" class="social-circle"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <h6 class="fw-bold mb-4 text-uppercase letter-spacing-1">Quick Links</h6>
                <a href="{{ route('home') }}" class="footer-link">Home</a>
                <a href="{{ route('aboutUs') }}" class="footer-link">About Us</a>
                <a href="{{ route('category.show') }}" class="footer-link">Products</a>
                <a href="{{ route('rAndD') }}" class="footer-link">R&D Projects</a>
                <a href="{{ route('inquire') }}" class="footer-link">Inquire Now</a>
            </div>
            <div class="col-lg-3 col-6">
                <h6 class="fw-bold mb-4 text-uppercase letter-spacing-1">Our Products</h6>
                @foreach ($categories as $category)
                    <a class="footer-link" href="{{ route('category.show', $category->slug) }}">{{$category->name}}</a>
                @endforeach
            </div>
            <div class="col-lg-3">
                <h6 class="fw-bold mb-4 text-uppercase letter-spacing-1">Contact Us</h6>
                <div class="d-flex gap-3 mb-3">
                    <i class="fas fa-map-marker-alt mt-1"></i>
                    <p class="small opacity-75">PFC Agri Solutions<br>Farm Road, Rural County<br>United Kingdom</p>
                </div>
                <div class="d-flex gap-3 mb-3">
                    <i class="fas fa-phone"></i>
                    <p class="small opacity-75">{{ $company->phone1 }}</p>
                </div>
                <div class="d-flex gap-3">
                    <i class="fas fa-envelope"></i>
                    <p class="small opacity-75">{{ $company->email1 }}</p>
                </div>
            </div>
        </div>
        <div class="footer-bottom text-center">
            <p class="mb-0">{{ $company->copyright }}</p>
        </div>
    </div>
</footer>

<style>
            /* --- Footer Section --- */
        .main-footer {
            background-color: #00a651; /* Branded green from image */
            color: white;
            padding: 80px 0 30px;
        }

        .footer-link {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            display: block;
            margin-bottom: 12px;
            transition: 0.3s;
        }

        .footer-link:hover {
            color: white;
            padding-left: 5px;
        }

        .social-circle {
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 10px;
            transition: 0.3s;
        }

        .social-circle:hover {
            background: white;
            color: var(--pfc-green);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 30px;
            margin-top: 50px;
            font-size: 0.9rem;
            opacity: 0.8;
        }
</style>