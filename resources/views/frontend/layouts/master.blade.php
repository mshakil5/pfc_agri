
<!doctype html>
<html lang="en">
<head>

    @php
        $company = App\Models\CompanyDetails::select('company_name', 'fav_icon', 'google_site_verification', 'footer_content', 'facebook', 'twitter', 'linkedin', 'website', 'phone1', 'email1', 'address1','company_logo','copyright','google_map')->first();
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PFC Agri Solutions - Innovative Agricultural Solutions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <title>{{ $company->meta_title ?? $company->company_name }}</title>
        {!! SEOMeta::generate() !!}
        {!! OpenGraph::generate() !!}
        {!! Twitter::generate() !!}



    @if($company->google_site_verification)
    <meta name="google-site-verification" content="{{ $company->google_site_verification }}">
    @endif
    <!-- Favicon -->
    <link href="{{ asset('images/company/' . $company->fav_icon) }}" rel="icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/company/' . $company->fav_icon) }}">

    <style>
        :root {
            --pfc-green: #00A651;
            --pfc-dark: #1a1a1a;
            --pfc-light-green: #d4edda;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        
        /* --- Top Bar Dropdown Fix --- */
        .top-bar { 
            background-color: var(--pfc-green); 
            color: white; 
            font-size: 0.85rem; 
            padding: 14px 0; 
            position: relative; /* Required for z-index to work */
            z-index: 1060;     /* Higher than Bootstrap's sticky-top (1020) */
        }

        /* 2. Fix Dropdown visibility and stacking */
        .top-bar .dropdown-menu { 
            font-size: 0.85rem; 
            z-index: 1070;      /* Ensure menu is even higher than the bar */
            margin-top: 5px !important; /* Spacing from the toggle */
        }

        /* 3. Ensure the navbar doesn't overlap when sticky */
        .sticky-top {
            z-index: 1020; /* Default Bootstrap sticky-top z-index */
        }

        /* --- Navbar Hover Dropdown --- */
        @media (min-width: 992px) {
            .nav-item.dropdown:hover .dropdown-menu {
                display: block;
                margin-top: 0; 
            }
        }
        .navbar-brand img { height: 50px; }
        .btn-inquire { background-color: var(--pfc-green); color: white; border-radius: 4px; padding: 8px 20px; text-decoration: none; }

        .nav-link {
            font-weight: 500;
            color: #333 !important;
            margin: 0 10px;
        }

        

        /* Product Cards */
        .category-card {
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            height: 400px;
            border: none;
            transition: transform 0.3s;
        }

        .category-card:hover {
            transform: translateY(-10px);
        }

        .card-img-overlay {
            background: linear-gradient(to top, rgba(0, 166, 81, 0.9) 0%, rgba(0,0,0,0.2) 60%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 30px;
            color: white;
        }

        .tag-pill {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 2px 12px;
            font-size: 0.75rem;
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-block;
        }

        .section-tag {
            color: var(--pfc-green);
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }



        /* --- About Us Section Styles --- */
        .about-section {
            padding: 80px 0;
            background-color: #F5FBF8;
        }

        .about-image-wrapper {
            position: relative;
            padding-bottom: 40px;
        }

        .main-about-img {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .experience-badge {
            position: absolute;
            bottom: 0;
            left: -20px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 200px;
        }

        .badge-number {
            background-color: #76ff03;
            color: #1a1a1a;
            font-weight: 800;
            font-size: 1.5rem;
            padding: 10px 15px;
            border-radius: 8px;
        }

        .about-title {
            color: var(--pfc-green);
            font-weight: 700;
            font-size: 2.5rem;
            line-height: 1.2;
        }

        .feature-box {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .feature-icon {
            min-width: 50px;
            height: 50px;
            background-color: #f0f9f4;
            color: var(--pfc-green);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 1.2rem;
        }

        .feature-content h6 {
            margin-bottom: 2px;
            font-weight: 700;
            color: var(--pfc-green);
        }

        .feature-content p {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0;
        }

        .btn-learn-more {
            background-color: var(--pfc-green);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-learn-more:hover {
            background-color: #008d44;
            color: white;
            transform: translateY(-2px);
        }



        /* --- Updated Blog Section Styles --- */
        .blog-section {
            padding: 80px 0;
            background-color: #fff;
        }

        .blog-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background-color: #f8fdfa;
            transition: all 0.4s ease; /* Smooth transition for shadow and lift */
            height: 100%;
            /* Default subtle shadow */
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .blog-card:hover {
            transform: translateY(-10px);
            /* Stronger shadow on hover */
            box-shadow: 0 15px 30px rgba(0, 166, 81, 0.15); 
        }

        .blog-img-wrapper {
            height: 250px;
            overflow: hidden; /* This keeps the zoomed image inside the borders */
            position: relative;
        }

        .blog-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease; /* Smooth zoom duration */
        }

        /* Zoom Effect on Hover */
        .blog-card:hover .blog-img-wrapper img {
            transform: scale(1.1); /* Zooms image by 10% */
        }

        .blog-content {
            padding: 25px;
        }

        .blog-meta {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 15px;
            display: flex;
            gap: 15px;
        }

        .blog-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--pfc-green);
            line-height: 1.4;
            margin-bottom: 15px;
            display: block;
            text-decoration: none;
        }

        .blog-excerpt {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 20px;
        }

        .read-more {
            color: var(--pfc-green);
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
        }



    </style>
</head>
<body>



    @include('frontend.inc.header')

        @yield('content')

    @include('frontend.cookies')

    @include('frontend.inc.footer')




    {{-- <a href="https://wa.me/447392597296" class="whatsapp-float" target="_blank" aria-label="Chat on WhatsApp" >
    <i class="bi bi-whatsapp"></i>
    </a>
    <style>
    .whatsapp-float {
        position: fixed;
        bottom: 50px;
        right: 25px;
        width: 60px;
        height: 60px;
        background-color: #ff708a;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        z-index: 9999;
        box-shadow: 0 6px 15px #ff708a;
        transition: all 0.3s ease-in-out;
    }

    .whatsapp-float:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 25px #ff708a;
        color: #fff;
    }

    /* Glow animation for attention */
    @keyframes pulse {
        0% {
        box-shadow: 0 0 0 0 #ff708a;
        }
        70% {
        box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
        }
        100% {
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
        }
    }

    .whatsapp-float {
        animation: pulse 2s infinite;
    }

    /* Mobile responsive adjustments */
    @media (max-width: 576px) {
        .whatsapp-float {
        width: 50px;
        height: 50px;
        font-size: 24px;
        bottom: 20px;
        right: 20px;
        }
    }
    </style> --}}



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@yield('script')


</body>
</html>


