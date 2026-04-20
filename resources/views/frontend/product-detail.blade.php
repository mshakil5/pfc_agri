@extends('frontend.layouts.master')

@section('content')

    {{-- Swiper CSS CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    
    <style>
        :root {
            --pfc-green: #00a651;
            --pfc-dark: #1a1a1a;
            --pfc-light: #f8fafc;
            --pfc-accent: #eef2f6;
        }

        body { font-family: 'Inter', sans-serif; background-color: white; color: #334155; }

        /* --- Breadcrumb Custom --- */
        .breadcrumb-section { background: var(--pfc-light); padding: 15px 0; border-bottom: 1px solid #e2e8f0; }
        
        /* --- Product Gallery (Swiper) --- */
        .main-swiper {
            background: var(--pfc-light);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--pfc-accent);
            padding: 20px;
        }
        .main-swiper .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }
        .main-swiper .swiper-slide img {
            max-width: 100%;
            max-height: 450px;
            object-fit: contain;
        }

        .thumb-swiper {
            padding: 10px 0 0 0;
        }
        .thumb-swiper .swiper-slide {
            width: 80px !important;
            height: 80px;
            opacity: 0.5;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s;
        }
        .thumb-swiper .swiper-slide-thumb-active {
            opacity: 1;
            border-color: var(--pfc-green);
        }
        .thumb-swiper .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* --- Product Info --- */
        .badge-category { background: #dcfce7; color: var(--pfc-green); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; padding: 5px 12px; border-radius: 50px; }
        .product-title { font-size: 2.5rem; font-weight: 800; color: var(--pfc-dark); margin: 15px 0; }
        
        /* --- Dynamic Features List --- */
        .features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            background: var(--pfc-light);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .feature-item {
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            font-weight: 500;
            color: #334155;
        }
        .feature-item i { 
            color: var(--pfc-green); 
            margin-right: 10px; 
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        /* --- Tabs & Content --- */
        .nav-pills-custom .nav-link {
            color: #64748b;
            font-weight: 600;
            padding: 12px 25px;
            border-radius: 0;
            border-bottom: 3px solid transparent;
        }
        .nav-pills-custom .nav-link.active {
            background: none;
            color: var(--pfc-green);
            border-bottom-color: var(--pfc-green);
        }

        .dynamic-specs-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .dynamic-specs-list li {
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
        }
        .dynamic-specs-list li::before {
            content: '';
            width: 6px;
            height: 6px;
            background: var(--pfc-green);
            border-radius: 50%;
            margin-right: 15px;
            flex-shrink: 0;
        }

        /* --- CTA Box --- */
        .inquiry-card {
            background: var(--pfc-dark);
            color: white;
            border-radius: 16px;
            padding: 30px;
            position: sticky;
            top: 100px;
        }
        .btn-pfc-lg {
            background: var(--pfc-green);
            color: white;
            width: 100%;
            padding: 15px;
            border-radius: 8px;
            font-weight: 700;
            text-transform: uppercase;
            border: none;
            transition: 0.3s;
        }
        .btn-pfc-lg:hover { background: #008d44; transform: translateY(-3px); color: white; }
    </style>


    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('category.show') }}" class="text-decoration-none text-muted">{{ __('Products') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#" class="text-decoration-none text-muted">
                            {{ $product->category?->translateOrNew(app()->getLocale())->name }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active fw-bold text-success">
                        {{ $product->translateOrNew(app()->getLocale())->title }}
                    </li>
                </ol>
            </nav>
        </div>
    </section>

    <main class="py-5">
        <div class="container">
            <div class="row g-5">

                {{-- LEFT SIDE --}}
                <div class="col-lg-7">
                    {{-- Main Image Slider --}}
                    <div class="swiper main-swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="{{ $product->image ? asset($product->image) : asset('placeholder.webp') }}" alt="{{ $product->translateOrNew(app()->getLocale())->title }}">
                            </div>
                            @if($product->images && count($product->images) > 0)
                                @foreach($product->images as $img)
                                    <div class="swiper-slide">
                                        <img src="{{ asset($img) }}" alt="Gallery Image">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- Thumbnail Slider --}}
                    <div class="swiper thumb-swiper" style="margin-top: 15px;">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="{{ $product->image ? asset($product->image) : asset('placeholder.webp') }}" alt="Thumb">
                            </div>
                            @if($product->images && count($product->images) > 0)
                                @foreach($product->images as $img)
                                    <div class="swiper-slide">
                                        <img src="{{ asset($img) }}" alt="Thumb">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- Tabs Section --}}
                    <div class="mt-5">
                        <ul class="nav nav-pills mb-4 nav-pills-custom" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#desc">{{ __('Overview') }}</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#specs">{{ __('Technical Specs') }}</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#docs">{{ __('Downloads') }}</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                            {{-- OVERVIEW --}}
                            <div class="tab-pane fade show active" id="desc">
                                {!! $product->translateOrNew(app()->getLocale())->long_description !!}
                            </div>

                            {{-- TECHNICAL SPECS (Dynamic Summernote Content) --}}
                            <div class="tab-pane fade" id="specs">
                                @if(!empty($specs))
                                    {!! $specs !!}
                                @else
                                    <p class="text-muted py-4 text-center">{{ __('No specifications added yet.') }}</p>
                                @endif
                            </div>

                            {{-- DOWNLOADS (Dynamic PDF Files) --}}
                            <div class="tab-pane fade" id="docs">
                                @if(count($downloads) > 0)
                                    <div class="list-group">
                                        @foreach($downloads as $dl)
                                            <a href="{{ asset($dl['path']) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                <span>
                                                    <i class="far fa-file-pdf me-2 text-danger"></i>
                                                    {{ $dl['name'] }}
                                                </span>
                                                <i class="fas fa-download text-muted"></i>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted py-4 text-center">{{ __('No downloads available for this product.') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDE --}}
                <div class="col-lg-5">
                    <div class="ps-lg-4">
                        <span class="badge-category">
                            {{ $product->category?->translateOrNew(app()->getLocale())->name }}
                        </span>

                        <h1 class="product-title">
                            {{ $product->translateOrNew(app()->getLocale())->title }}
                        </h1>

                        {{-- Price Removed Completely --}}

                        <p class="text-muted mb-4">
                            {!! $product->translateOrNew(app()->getLocale())->short_description !!}
                        </p>

                        {{-- Dynamic Features Grid --}}
                        @if(count($features) > 0)
                            <div class="features-grid">
                                @foreach($features as $feature)
                                    <div class="feature-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Inquiry Form --}}
                        <div class="inquiry-card shadow-lg mt-4">
                            <h5 class="fw-bold mb-3">{{ __('Request a Quote') }}</h5>
                            <p class="small opacity-75 mb-4">
                                {{ __('Our specialists will contact you within 24 hours with a custom quote and configuration advice.') }}
                            </p>

                            <form>
                                <div class="mb-3">
                                    <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="{{ __('Full Name') }}">
                                </div>
                                <div class="mb-3">
                                    <input type="email" class="form-control bg-dark text-white border-secondary" placeholder="{{ __('Email Address') }}">
                                </div>
                                <div class="mb-3">
                                    <input type="tel" class="form-control bg-dark text-white border-secondary" placeholder="{{ __('Phone Number') }}">
                                </div>
                                <div class="mb-4">
                                    <select class="form-select bg-dark text-white border-secondary">
                                        <option selected disabled>{{ __('Select Model') }}</option>
                                        <option>{{ __('John Deere') }}</option>
                                        <option>{{ __('New Holland') }}</option>
                                        <option>{{ __('Other') }}</option>
                                    </select>
                                </div>
                                <button class="btn-pfc-lg" type="button">
                                    {{ __('Submit Inquiry') }} <i class="fas fa-paper-plane ms-2"></i>
                                </button>
                            </form>

                            <div class="mt-4 pt-4 border-top border-secondary text-center">
                                <p class="small mb-0 opacity-50">{{ __('Need immediate help?') }}</p>
                                <p class="fw-bold">{{ __('+44 (0) 1234 567890') }}</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </main>

@endsection

@section('script')
    {{-- Swiper JS CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var thumbSwiper = new Swiper(".thumb-swiper", {
                spaceBetween: 10,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesProgress: true,
            });

            var mainSwiper = new Swiper(".main-swiper", {
                spaceBetween: 10,
                loop: false,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                thumbs: {
                    swiper: thumbSwiper,
                },
            });
        });
    </script>
@endsection