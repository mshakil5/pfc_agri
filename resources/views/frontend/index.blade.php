@extends('frontend.layouts.master')

@section('content')


    <style>
        /* --- Professional Hero & Stats --- */
        .hero-section {
            position: relative;
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
            padding: 100px 0; /* More balanced padding */
            overflow: hidden;
        }

        /* The Gradient Overlay */
        .hero-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Adjust the '0.9' for darkness and '60%' for where the fade starts */
            background: linear-gradient(to right, 
                rgba(0, 50, 0, 0.9) 0%, 
                rgba(0, 50, 0, 0.6) 40%, 
                rgba(0, 50, 0, 0) 100%);
            z-index: 1;
        }

        /* Ensure content sits above the gradient */
        .hero-section .container {
            position: relative;
            z-index: 2;
        }

        .hero-badge { 
            background: rgba(118, 255, 3, 0.2); /* Tinted with your highlight color */
            border: 1px solid rgba(118, 255, 3, 0.4);
            color: #76ff03;
            padding: 6px 18px; 
            border-radius: 50px; 
            display: inline-block; 
            margin-bottom: 25px; 
            font-size: 0.9rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem); /* Responsive font sizing */
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
        }

        /* This is the green color for the second part of the title */
        .text-highlight {
            color: #7FD13B; /* A vibrant agricultural green */
            display: inline-block; /* Helps with spacing on some browsers */
        }

        /* Optional: Add a text shadow if the image is very busy */
        .hero-title span {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        /* Professional Stat Cards */
        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 25px 15px;
            text-align: center;
            border-radius: 12px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.1);
        }

        .stat-card h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #76ff03;
            margin-bottom: 5px;
        }

        /* --- Mobile Responsiveness --- */
        @media (max-width: 991px) {
            .hero-section {
                text-align: center;
                min-height: auto;
            }
            
            .hero-section::before {
                /* Change gradient to bottom-up on mobile for better text reading */
                background: linear-gradient(to bottom, 
                    rgba(0, 50, 0, 0.8) 0%, 
                    rgba(0, 50, 0, 0.5) 100%);
            }

            .hero-section .btn-lg {
                width: 100%;
                margin-bottom: 10px;
                margin-right: 0 !important;
            }
        }

        @media (max-width: 768px) {
            .text-highlight {
                display: block; /* Moves the green text to a new line on mobile for impact */
                margin-top: 5px;
            }
        }
    </style>

    <section class="hero-section" style="background-image: url('{{ asset('images/slider/' . $slider->image) }}')">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="hero-badge">{{ $slider->hero_badge }}</div>
                    
                    <h1 class="hero-title">
                        @php
                            $words = explode(' ', $slider->title);
                            $firstPart = implode(' ', array_slice($words, 0, 2));
                            $secondPart = implode(' ', array_slice($words, 2));
                        @endphp

                        <span class="text-white">{{ $firstPart }}</span>
                        <span class="text-highlight">{{ $secondPart }}</span>
                    </h1>
                    
                    <p class="lead my-4 opacity-75">
                        {{ $slider->sub_title }}
                    </p>

                    <div class="mt-5 mb-5">
                        @foreach($slider->buttons as $btn)
                            <a href="{{ $btn['link'] }}" class="btn btn-light btn-lg px-4 me-3 text-success fw-bold rounded-1">
                                {{ $btn['label'] }} &rarr;
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-12 stats-container">
                    <div class="row g-3">
                        {{-- Directly loop through the attribute --}}
                        @foreach($slider->stat_card as $stat)
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <h3>{{ $stat['value'] ?? '' }}</h3>
                                    <p class="mb-0 small opacity-75">{{ $stat['title'] ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="py-5 mt-5">
        <div class="container text-center mb-5">
            <p class="section-tag mb-1">{{ __('index.what_we_offer') }}</p>
            <h2 class="fw-bold mb-3" style="color: #00a651;">{{ __('index.our_product_categories') }}</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">
                {{ __('index.product_categories_description') }}
            </p>
        </div>

        <div class="container">
            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-md-6">
                        <div class="card category-card">
                            <img src="{{ asset($category->image) }}"
                                class="card-img h-100"
                                style="object-fit: cover;"
                                alt="{{ $category->translateOrNew(app()->getLocale())->name ?? $category->name }}">

                            <div class="card-img-overlay">
                                <h3>{{ $category->translateOrNew(app()->getLocale())->name ?? $category->name }}</h3>

                                <p class="small opacity-75">{{ $category->translateOrNew(app()->getLocale())->description ?? $category->description }}</p>

                                <div class="mb-3">
                                    @foreach($category->products->take(4) as $product)
                                        <span class="tag-pill">{{ $product->title }}</span>
                                    @endforeach
                                </div>

                                <a href="{{ route('category.show', $category->slug) }}" class="text-white text-decoration-none fw-bold">
                                    {{ __('index.view_products') }} &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @if($about)
    <section class="about-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="about-image-wrapper">
                        <img src="{{ asset('images/about/' . $about->image) }}"
                            alt="{{ $about->getTranslation(app()->getLocale(), 'title') }}"
                            class="main-about-img">
                        <div class="experience-badge">
                            <div class="badge-number">{{ $about->year }}+</div>
                            <div>
                                <div class="fw-bold mb-0">{{ __('about.years') }}</div>
                                <small class="text-muted">{{ __('about.in_business') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <p class="section-tag mb-2">{{ $about->getTranslation(app()->getLocale(), 'title') }}</p>
                    <h2 class="about-title mb-4">{{ $about->getTranslation(app()->getLocale(), 'sub_title') }}</h2>
                    <div class="text-muted mb-5">
                        {!! $about->getTranslation(app()->getLocale(), 'long_description') !!}
                    </div>

                    <div class="row">
                        @php
                            $locale    = app()->getLocale();
                            $trans     = $about->translations[$locale] ?? [];
                            $amenities = !empty($trans['amenities'])
                                ? $trans['amenities']
                                : (json_decode($about->amenities, true) ?? []);
                        @endphp

                        @foreach($amenities as $item)
                            <div class="col-sm-6">
                                <div class="feature-box">
                                    <div class="feature-icon"><i class="{{ $item['icon'] ?? '' }}"></i></div>
                                    <div class="feature-content">
                                        <h6>{{ $item['title'] ?? '' }}</h6>
                                        <p>{{ $item['subtitle'] ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('aboutUs') }}" class="btn btn-learn-more">
                            {{ __('about.learn_more') }} &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <section class="blog-section">
        <div class="container">
            <div class="text-center mb-5">
                <p class="section-tag mb-1">{{ __('blog.from_our_blog') }}</p>
                <h2 class="fw-bold mb-3" style="color: #00a651;">{{ __('blog.from_our_blog') }}
                </h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">{{ __('Stay updated with the latest farming tips, product news, and industry insights.') }}</p>
            </div>

            <div class="row g-4">
                @foreach($blogs as $blog)
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-card">
                            <div class="blog-img-wrapper">
                                {{-- Check if image exists, otherwise show placeholder --}}
                                <img src="{{ $blog->image ? asset($blog->image) : 'https://via.placeholder.com/600x400?text=No+Image' }}" 
                                    alt="{{ $blog->title }}">
                            </div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    {{-- Format date to: Jan 5, 2026 --}}
                                    <span><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') }}</span>
                                    <span><i class="far fa-user"></i> {{ $blog->author_name }}</span>
                                </div>
                                
                                {{-- Dynamic Title and Slug --}}
                                <a href="{{ route('blog.show', $blog->slug) }}" class="blog-title">
                                    {{ $blog->title }}
                                </a>
                                
                                <p class="blog-excerpt">
                                    {{ Str::limit($blog->excerpt, 100) }}
                                </p>
                                
                                <a href="{{ route('blog.show', $blog->slug) }}" class="read-more">{{ __('Read More') }} &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>



    <section class="dealer-section">
        <div class="container">
        <div class="text-center mb-5">
            <p class="section-tag mb-1">{{ __('partners.our_partners') }}</p>
            <h2 class="fw-bold mb-3" style="color: #00a651;">{{ __('partners.authorized_dealer_network') }}</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">
                {{ __('partners.find_dealer_description') }}
            </p>
        </div>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div id="map"></div>
                </div>

                <div class="col-lg-5">
                    <div class="address-list-container" id="dealerList">
                        <div class="dealer-card active" data-lat="54.6078" data-lng="-5.9264" data-id="1">
                            <h6 class="dealer-name">Northern Ireland Farm Tech</h6>
                            <div class="dealer-info"><i class="fas fa-map-marker-alt me-2"></i> Northern Ireland</div>
                            <div class="dealer-info"><i class="fas fa-phone me-2"></i> +44 28 555 0890</div>
                            <div class="dealer-info"><i class="fas fa-external-link-alt me-2"></i> Visit Website</div>
                            <div class="dealer-tags mt-2">
                                <span class="tag-pill">Field Preparation</span>
                                <span class="tag-pill">Slurry Management</span>
                            </div>
                        </div>

                        <div class="dealer-card" data-lat="57.4778" data-lng="-4.2247" data-id="2">
                            <h6 class="dealer-name">Scottish Highlands Equipment</h6>
                            <div class="dealer-info"><i class="fas fa-map-marker-alt me-2"></i> Scotland</div>
                            <div class="dealer-info"><i class="fas fa-phone me-2"></i> +44 131 555 0234</div>
                            <div class="dealer-info"><i class="fas fa-external-link-alt me-2"></i> Visit Website</div>
                            <div class="dealer-tags mt-2">
                                <span class="tag-pill">Field Preparation</span>
                                <span class="tag-pill">Woodland Management</span>
                            </div>
                        </div>

                        <div class="dealer-card" data-lat="50.8225" data-lng="-0.1372" data-id="3">
                            <h6 class="dealer-name">South Coast Farming Solutions</h6>
                            <div class="dealer-info"><i class="fas fa-map-marker-alt me-2"></i> South East</div>
                            <div class="dealer-info"><i class="fas fa-phone me-2"></i> +44 1273 555 0789</div>
                            <div class="dealer-tags mt-2">
                                <span class="tag-pill">Slurry Management</span>
                                <span class="tag-pill">Wet Bale Management</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <style>
        /* --- Dealer Network Styles --- */
        .dealer-section {
            padding: 80px 0;
            background-color: #f9fbf9;
        }

        #map {
            height: 500px;
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            z-index: 1;
        }

        .address-list-container {
            height: 500px;
            overflow-y: auto;
            padding-right: 10px;
        }

        /* Custom Scrollbar */
        .address-list-container::-webkit-scrollbar { width: 6px; }
        .address-list-container::-webkit-scrollbar-track { background: #f1f1f1; }
        .address-list-container::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }

        .dealer-card {
            background: white;
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }

        .dealer-card:hover, .dealer-card.active {
            border-color: var(--pfc-green);
            box-shadow: 0 10px 20px rgba(0, 166, 81, 0.1);
            transform: translateX(5px);
        }

        .dealer-name {
            color: var(--pfc-green);
            font-weight: 700;
            margin-bottom: 5px;
        }

        .dealer-info {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 3px;
        }

        .dealer-tags .tag-pill {
            background: #e9f7ef;
            color: #2d6a4f;
            border: none;
            font-size: 0.7rem;
        }
    </style>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Initialize Map
        const map = L.map('map').setView([54.5, -3.5], 6); // Centered on UK

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        const markers = {};
        const cards = document.querySelectorAll('.dealer-card');

        // Custom Icon
        const greenIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div style='background-color:#00a651; width:12px; height:12px; border-radius:50%; border:2px solid white; box-shadow:0 0 5px rgba(0,0,0,0.3);'></div>",
            iconSize: [12, 12],
            iconAnchor: [6, 6]
        });

        // Add Markers and Handle Clicks
        cards.forEach(card => {
            const lat = card.dataset.lat;
            const lng = card.dataset.lng;
            const id = card.dataset.id;

            const marker = L.marker([lat, lng], { icon: greenIcon }).addTo(map);
            markers[id] = marker;

            card.addEventListener('click', () => {
                // Remove active class from all
                cards.forEach(c => c.classList.remove('active'));
                // Add to clicked
                card.classList.add('active');
                // Pan Map
                map.flyTo([lat, lng], 8);
                marker.openPopup();
            });
        });
    </script>


    <style>
        /* --- Awards Section --- */
        .awards-section {
            padding: 80px 0;
            background-color: #fff;
        }

        .award-card {
            background: #f8fdfa; /* Light tint for card contrast on white bg */
            border: 1px solid #e9f7ef;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            height: 100%;
            transition: all 0.3s ease;
        }

        .award-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 166, 81, 0.1);
            border-color: var(--pfc-green);
        }

        .award-icon-circle {
            width: 60px;
            height: 60px;
            background: var(--pfc-green);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.5rem;
        }

        /* --- CTA Section --- */
        .cta-section {
            padding: 80px 0;
            background-color: #F5FBF8; /* Requested Light Green */
        }

        .contact-info-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 20px;
            height: 100%;
        }

        .contact-icon-box {
            width: 50px;
            height: 50px;
            background: #e9f7ef;
            color: var(--pfc-green);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }


    </style>




    <section class="awards-section">
        <div class="container text-center">
            <p class="section-tag mb-1">{{ __('RECOGNITION') }}</p>
            <h2 class="fw-bold mb-3" style="color: #00a651;">{{ __('Awards & Achievements') }}</h2>
            <p class="text-muted mx-auto mb-5" style="max-width: 600px;">
                {{ __('Our commitment to excellence has been recognized by industry leaders and organizations.') }}
            </p>
                        
            <div class="row g-4 text-start">
                @foreach($awards as $award)
                    <div class="col-md-4">
                        <div class="award-card">
                            {{-- Non-translatable data (icon) --}}
                            <div class="award-icon-circle">
                                <i class="{{ $award->icon }}"></i>
                            </div>

                            {{-- Translatable data (title) --}}
                            <h5 class="fw-bold">{{ $award->title }}</h5>

                            {{-- Combination of translatable (organization) and static (year) --}}
                            <p class="small text-muted mb-2">
                                {{ $award->organization }} • {{ $award->year }}
                            </p>

                            {{-- Translatable Tag --}}
                            @if($award->tag)
                                <span class="tag-pill mb-3">{{ $award->tag }}</span>
                            @endif

                            {{-- Translatable Description --}}
                            <div class="small text-muted mt-2">
                                {!! $award->description !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <h2 class="fw-bold mb-3" style="color: #00a651;">
                        {{ __('Ready to Transform Your Farming Operation?') }}
                    </h2>
                    <p class="text-muted mb-4">
                        {{ __('Get in touch with our team to discuss your requirements. We\'re here to help you find the right solutions for your agricultural needs.') }}
                    </p>

                    <div class="d-flex gap-3">
                        <a href="{{route('inquire')}}" class="btn btn-success btn-lg px-4 rounded-1" style="background-color: var(--pfc-green);">{{ __('Inquire Now') }} &rarr;</a>
                        <a href="{{route('category.show')}}" class="btn btn-outline-success btn-lg px-4 rounded-1">{{ __('Browse Products') }}
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="contact-info-card">
                                <div class="contact-icon-box"><i class="fas fa-phone"></i></div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ __('Call Us') }}</h6>
                                    <p class="mb-0 text-muted"> {{ $company->phone1 }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="contact-info-card">
                                <div class="contact-icon-box"><i class="fas fa-envelope"></i></div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ __('Email Us') }}</h6>
                                    <p class="mb-0 text-muted"> {{ $company->email1 }} </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




@endsection

@section('script')


@endsection