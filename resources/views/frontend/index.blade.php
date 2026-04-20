@extends('frontend.layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />


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
            padding: 100px 0;
            overflow: hidden;
        }

        .hero-section::before {
            content: "";
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(to right, 
                rgba(0, 50, 0, 0.9) 0%, 
                rgba(0, 50, 0, 0.6) 40%, 
                rgba(0, 50, 0, 0) 100%);
            z-index: 1;
        }

        .hero-section .container { position: relative; z-index: 2; }

        /* Carousel overrides */
        .carousel, .carousel-inner, .carousel-item { height: 100%; }

        .carousel-item .hero-section {
            background-attachment: scroll;
        }

        /* Fade transition instead of slide (looks better for full-screen heroes) */
        #heroCarousel .carousel-item {
            transition: opacity 0.8s ease-in-out;
            opacity: 0;
            /* Remove default transform-based slide */
            transform: none !important;
            position: absolute;
            width: 100%;
        }

        #heroCarousel .carousel-item.active {
            opacity: 1;
            position: relative;
        }

        /* Carousel control buttons */
        #heroCarousel .carousel-control-prev,
        #heroCarousel .carousel-control-next {
            z-index: 10;
            width: 5%;
        }

        /* Carousel indicators */
        #heroCarousel .carousel-indicators [data-bs-target] {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: rgba(118, 255, 3, 0.5);
            border: 1px solid #76ff03;
        }

        #heroCarousel .carousel-indicators .active {
            background-color: #76ff03;
        }

        .hero-badge { 
            background: rgba(118, 255, 3, 0.2);
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
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
        }

        .text-highlight {
            color: #7FD13B;
            display: inline-block;
        }

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

        .stat-card:hover { transform: translateY(-5px); background: rgba(255, 255, 255, 0.1); }
        .stat-card h3 { font-size: 1.8rem; font-weight: 700; color: #76ff03; margin-bottom: 5px; }

        @media (max-width: 991px) {
            .hero-section { text-align: center; min-height: auto; }
            .hero-section::before {
                background: linear-gradient(to bottom, rgba(0,50,0,0.8) 0%, rgba(0,50,0,0.5) 100%);
            }
            .hero-section .btn-lg { width: 100%; margin-bottom: 10px; margin-right: 0 !important; }
        }

        @media (max-width: 768px) {
            .text-highlight { display: block; margin-top: 5px; }
        }
    </style>

    {{-- Carousel Indicators --}}
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">

        <div class="carousel-indicators">
            @foreach($sliders as $index => $slider)
                <button type="button" 
                        data-bs-target="#heroCarousel" 
                        data-bs-slide-to="{{ $index }}"
                        class="{{ $index === 0 ? 'active' : '' }}"
                        aria-label="Slide {{ $index + 1 }}">
                </button>
            @endforeach
        </div>

        {{-- Carousel Slides --}}
        <div class="carousel-inner">
            @foreach($sliders as $index => $slider)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <section class="hero-section" 
                            style="background-image: url('{{ asset('images/slider/' . $slider->image) }}')">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-7">
                                    @if ($slider->hero_badge)
                                        
                                    <div class="hero-badge">{{ $slider->hero_badge }}</div>
                                    @endif

                                    <h1 class="hero-title">
                                        @php
                                            $words = explode(' ', $slider->title);
                                            $firstPart = implode(' ', array_slice($words, 0, 2));
                                            $secondPart = implode(' ', array_slice($words, 2));
                                        @endphp
                                        <span class="text-white">{{ $firstPart }}</span>
                                        <span class="text-highlight">{{ $secondPart }}</span>
                                    </h1>

                                    <p class="lead my-4 opacity-75">{{ $slider->sub_title }}</p>

                                    <div class="mt-5 mb-5">
                                        @foreach($slider->buttons as $btn)
                                            <a href="{{ $btn['link'] }}" 
                                            class="btn btn-light btn-lg px-4 me-3 text-success fw-bold rounded-1">
                                                {{ $btn['label'] }} &rarr;
                                            </a>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-lg-12 stats-container">
                                    <div class="row g-3">
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
                </div>
            @endforeach
        </div>

        {{-- Prev / Next Controls --}}
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>

    </div>


    <section class="py-5 mt-5">
        <div class="container text-center mb-5">
            <p class="section-tag mb-1 d-none">{{ __('index.what_we_offer') }}</p>
            <h2 class="fw-bold mb-3" style="color: #00a651;">{{ __('index.our_product_categories') }}</h2>
            <p class="text-muted mx-auto" style="max-width: 800px;">
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
    .awards-section { 
        padding: 80px 0; 
        background-color: #ffffff; 
    }

    .awardSwiper {
        padding: 20px 10px 50px;
        overflow: hidden;
    }

    .award-card {
        background: #f8fdfa;
        border: 1px solid #e9f7ef;
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: row; /* Left/Right split */
        align-items: stretch;
        text-align: left;
        max-width: 900px; 
        margin: 0 auto;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        transition: transform 0.3s ease;
    }

    .award-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 166, 81, 0.1);
    }

    /* LEFT SIDE (Image) */
    .award-image-wrapper {
        width: 35%;
        background: #e9f7ef;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .award-image-wrapper img {
        max-width: 100%;
        max-height: 220px;
        object-fit: contain;
        filter: drop-shadow(0 5px 10px rgba(0,0,0,0.1));
    }

    /* RIGHT SIDE (Data) */
    .award-content {
        width: 65%;
        padding: 35px 35px 35px 15px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .award-meta {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .award-meta strong {
        color: #334155;
    }

    .tag-pill {
        background: #00a651;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 15px;
        width: fit-content;
    }

    .description-container {
        max-height: 4.5em; 
        overflow: hidden;
        transition: max-height 0.4s ease;
        font-size: 0.9rem;
        color: #64748b;
        line-height: 1.6;
    }

    .description-container.expanded {
        max-height: 1000px;
    }

    .toggle-btn {
        color: #00a651;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.8rem;
        margin-top: 10px;
        display: inline-block;
        transition: 0.2s;
    }

    .toggle-btn:hover {
        color: #008d44;
    }

    /* Swiper Settings */
    .swiper-pagination-bullet-active {
        background: #00a651 !important;
    }

    /* Responsive Design */
    @media (max-width: 767px) {
        .award-card {
            flex-direction: column;
            text-align: center;
            max-width: 400px;
        }
        .award-image-wrapper {
            width: 100%;
            padding: 25px 25px 0;
        }
        .award-content {
            width: 100%;
            padding: 25px;
        }
        .tag-pill {
            margin-left: auto;
            margin-right: auto;
        }
    }
</style>

<section class="awards-section">
    <div class="container">
        <div class="text-center mb-5">
            <p class="section-tag mb-1">{{ __('RECOGNITION') }}</p>
            <h2 class="fw-bold mb-3" style="color: #00a651;">{{ __('Awards & Achievements') }}</h2>
        </div>

        <div class="swiper awardSwiper">
            <div class="swiper-wrapper">
                @foreach($awards as $index => $award)
                    @php
                        // Safely get translation based on your custom model setup
                        $trans = $award->translateOrNew(app()->getLocale());
                    @endphp
                    <div class="swiper-slide">
                        <div class="award-card">
                            {{-- LEFT SIDE: Image --}}
                            <div class="award-image-wrapper">
                                <img src="{{ $award->image ? asset($award->image) : asset('placeholder.webp') }}" alt="{{ $trans->title }}">
                            </div>
                            
                            {{-- RIGHT SIDE: Content --}}
                            <div class="award-content">
                                @if($trans->tag)
                                    <span class="tag-pill">{{ $trans->tag }}</span>
                                @endif

                                <h4 class="fw-bold mb-2" style="color: #1a1a1a; font-size: 1.3rem;">{{ $trans->title }}</h4>
                                
                                <p class="award-meta">
                                    <strong>{{ $trans->organization }}</strong> • {{ $award->year }}
                                </p>

                                <div class="description-wrapper">
                                    <div class="description-container" id="desc-{{ $loop->index }}">
                                        {!! $trans->description !!}
                                    </div>
                                    @if(!empty($trans->description))
                                        <span class="toggle-btn" onclick="toggleDescription({{ $loop->index }}, this)">
                                            {{ __('Read More') }} <i class="fas fa-chevron-down ms-1" style="font-size: 0.7rem;"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="swiper-pagination mt-4"></div>
        </div>
    </div>
</section>





    <style>

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

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    const awardSwiper = new Swiper('.awardSwiper', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        speed: 800,
        grabCursor: true,
    });

    function toggleDescription(index, btn) {
        const desc = document.getElementById(`desc-${index}`);
        const icon = btn.querySelector('i'); // Grab the chevron icon
        
        if (desc.classList.contains('expanded')) {
            desc.classList.remove('expanded');
            btn.innerHTML = `{{ __('Read More') }} <i class="fas fa-chevron-down ms-1" style="font-size: 0.7rem;"></i>`;
            awardSwiper.update(); // Recalculate slide height
        } else {
            desc.classList.add('expanded');
            btn.innerHTML = `{{ __('Show Less') }} <i class="fas fa-chevron-up ms-1" style="font-size: 0.7rem;"></i>`;
            awardSwiper.update(); // Recalculate slide height
        }
    }
</script>

@endsection