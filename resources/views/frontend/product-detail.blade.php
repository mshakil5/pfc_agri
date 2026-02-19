@extends('frontend.layouts.master')

@section('content')



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
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
        
        /* --- Product Gallery --- */
        .main-product-img {
            background: var(--pfc-light);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--pfc-accent);
        }
        .thumb-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-top: 15px; }
        .thumb-item { 
            aspect-ratio: 1; border-radius: 8px; cursor: pointer; border: 2px solid transparent; 
            overflow: hidden; transition: 0.3s;
        }
        .thumb-item:hover, .thumb-item.active { border-color: var(--pfc-green); }
        .thumb-item img { width: 100%; height: 100%; object-fit: cover; }

        /* --- Product Info --- */
        .badge-category { background: #dcfce7; color: var(--pfc-green); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; padding: 5px 12px; border-radius: 50px; }
        .product-title { font-size: 2.5rem; font-weight: 800; color: var(--pfc-dark); margin: 15px 0; }
        .price-tag { font-size: 1.75rem; font-weight: 700; color: var(--pfc-green); margin-bottom: 20px; }
        
        .spec-shortcut {
            background: var(--pfc-light);
            border-radius: 12px;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }
        .shortcut-item i { color: var(--pfc-green); margin-right: 10px; }
        .shortcut-item span { font-size: 0.9rem; font-weight: 500; }

        /* --- Tabs & Content --- */
        .nav-pills-custom .nav-link {
            color: var(--pfc-gray);
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

        .tech-table tr td:first-child { font-weight: 600; background: var(--pfc-light); width: 30%; }

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

        .feature-icon-box {
            width: 50px; height: 50px; background: #f0fdf4; 
            color: var(--pfc-green); border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem; margin-bottom: 15px;
        }
    </style>


    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('allproducts') }}" class="text-decoration-none text-muted">
                            {{ __('Products') }}
                        </a>
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
                    <div class="main-product-img">
                        <img src="{{ $product->image ? asset($product->image) : asset('placeholder.webp') }}"
                            class="img-fluid"
                            id="mainImg"
                            alt="{{ $product->translateOrNew(app()->getLocale())->title }}">
                    </div>

                    {{-- THUMBNAILS --}}
                    <div class="thumb-grid">

                        {{-- main image thumb --}}
                        <div class="thumb-item active">
                            <img src="{{ $product->image ? asset($product->image) : asset('placeholder.webp') }}"
                                onclick="document.getElementById('mainImg').src=this.src">
                        </div>

                        {{-- extra images (if you have) --}}
                        @if(isset($product->images) && $product->images->count())
                            @foreach($product->images as $img)
                                <div class="thumb-item">
                                    <img src="{{ asset($img->image) }}"
                                        onclick="document.getElementById('mainImg').src=this.src">
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="mt-5">
                        <ul class="nav nav-pills mb-4 nav-pills-custom" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#desc">
                                    {{ __('Overview') }}
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#specs">
                                    {{ __('Technical Specs') }}
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#docs">
                                    {{ __('Downloads') }}
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">

                            {{-- OVERVIEW --}}
                            <div class="tab-pane fade show active" id="desc">
                                {!! $product->translateOrNew(app()->getLocale())->long_description !!}
                            </div>

                            {{-- TECH SPECS (static for now) --}}
                            <div class="tab-pane fade" id="specs">
                                <table class="table table-bordered tech-table">
                                    <tr>
                                        <td>{{ __('Compatible Balers') }}</td>
                                        <td>{{ __('N/A') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Tank Capacity') }}</td>
                                        <td>{{ __('N/A') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Pump Type') }}</td>
                                        <td>{{ __('N/A') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Control System') }}</td>
                                        <td>{{ __('N/A') }}</td>
                                    </tr>
                                </table>
                            </div>

                            {{-- DOWNLOADS (static for now) --}}
                            <div class="tab-pane fade" id="docs">
                                <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between">
                                        <span>
                                            <i class="far fa-file-pdf me-2 text-danger"></i>
                                            {{ __('Installation Manual.pdf') }}
                                        </span>
                                        <i class="fas fa-download"></i>
                                    </a>

                                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between">
                                        <span>
                                            <i class="far fa-file-pdf me-2 text-danger"></i>
                                            {{ __('Product Brochure.pdf') }}
                                        </span>
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
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

                        <div class="price-tag">
                            @if($product->price)
                                {{ number_format($product->price, 2) }}
                            @else
                                {{ __('Price on Request') }}
                            @endif
                        </div>

                        <p class="text-muted mb-4">
                            {!! $product->translateOrNew(app()->getLocale())->short_description !!}
                        </p>

                        {{-- Static shortcuts --}}
                        <div class="spec-shortcut">
                            <div class="shortcut-item"><i class="fas fa-check-circle"></i><span>{{ __('Plug & Play') }}</span></div>
                            <div class="shortcut-item"><i class="fas fa-check-circle"></i><span>{{ __('Universal Fit') }}</span></div>
                            <div class="shortcut-item"><i class="fas fa-check-circle"></i><span>{{ __('GPS Ready') }}</span></div>
                            <div class="shortcut-item"><i class="fas fa-check-circle"></i><span>{{ __('2 Year Warranty') }}</span></div>
                        </div>

                        {{-- Inquiry --}}
                        <div class="inquiry-card shadow-lg">
                            <h5 class="fw-bold mb-3">{{ __('Request a Quote') }}</h5>
                            <p class="small opacity-75 mb-4">
                                {{ __('Our specialists will contact you within 24 hours with a custom quote and configuration advice.') }}
                            </p>

                            <form>
                                <div class="mb-3">
                                    <input type="text" class="form-control bg-dark text-white border-secondary"
                                        placeholder="{{ __('Full Name') }}">
                                </div>
                                <div class="mb-3">
                                    <input type="email" class="form-control bg-dark text-white border-secondary"
                                        placeholder="{{ __('Email Address') }}">
                                </div>
                                <div class="mb-4">
                                    <select class="form-select bg-dark text-white border-secondary">
                                        <option selected>{{ __('Select Model') }}</option>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection

@section('script')


@endsection