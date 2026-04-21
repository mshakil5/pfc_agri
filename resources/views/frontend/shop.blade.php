@extends('frontend.layouts.master')

@section('content')
<style>
    :root { --pfc-green: #00a651; }
    
    /* --- Hero --- */
    .product-hero {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1600&q=80');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 60px 0;
        margin-bottom: 50px;
    }

    /* --- Sidebar Navigation --- */
    .category-sidebar {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        position: sticky;
        top: 20px;
    }

    .sidebar-title { color: var(--pfc-green); font-weight: 700; margin-bottom: 20px; border-bottom: 2px solid #f0fdf4; padding-bottom: 10px; }
    
    .main-cat {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        color: #444;
        text-decoration: none !important;
        border-radius: 8px;
        font-weight: 500;
        transition: 0.3s;
        margin-bottom: 5px;
    }

    .main-cat.active { background-color: var(--pfc-green); color: white !important; }
    .main-cat:hover:not(.active) { background-color: #f0fdf4; color: var(--pfc-green); }

    /* --- Product Cards --- */
    .p-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #eef2f6;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .p-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
    
    .p-img-box { 
        height: 200px; 
        overflow: hidden; 
        background: #f8f9fa; 
    }
    .p-img-box a {
        display: block;
        width: 100%;
        height: 100%;
    }
    .p-img-box img { 
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
        transition: transform 0.4s ease;
    }
    .p-card:hover .p-img-box img {
        transform: scale(1.05);
    }

    /* Clickable Title */
    .p-title-link {
        color: #222;
        text-decoration: none;
        transition: color 0.2s;
    }
    .p-title-link:hover {
        color: var(--pfc-green);
    }

    .active-filter-pill {
        background: #f0fdf4;
        color: var(--pfc-green);
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        border: 1px solid var(--pfc-green);
        font-weight: 600;
    }
</style>

<header class="product-hero">
    <div class="container text-center text-md-start">
        <h1 class="fw-bold display-5">
            {{ __('footer.our_products') }}
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 justify-content-center justify-content-md-start">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}" class="text-white opacity-75 text-decoration-none">{{ __('header.home') }}</a>
                </li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ $currentCategory ? ($currentCategory->translateOrNew(app()->getLocale())->name ?? $currentCategory->name) : __('shop.our_product_range') }}</li>
            </ol>
        </nav>
    </div>
</header>

<main class="pb-5">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <div class="category-sidebar">
                    <h6 class="sidebar-title text-uppercase small">{{ __('Categories') }}</h6>

                    <a href="{{ route('category.show') }}" class="main-cat {{ !$currentCategory ? 'active' : '' }}">
                        <span>{{ __('All Products') }}</span>
                        <i class="fas fa-chevron-right small opacity-50"></i>
                    </a>

                    @foreach($categories as $cat)
                        <a href="{{ route('category.show', $cat->slug) }}"
                           class="main-cat {{ ($currentCategory && $currentCategory->id == $cat->id) ? 'active' : '' }}">
                            <span>{{ $cat->translateOrNew(app()->getLocale())->name ?? $cat->name }}</span>
                            <span class="badge rounded-pill {{ ($currentCategory && $currentCategory->id == $cat->id) ? 'bg-white text-success' : 'bg-light text-dark' }}">
                                {{ $cat->products_count }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </aside>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 bg-white p-3 rounded-3 shadow-sm">
                    <div>
                        @if($currentCategory)
                            <div class="active-filter-pill">
                                {{ __('Categories') }}: {{ $currentCategory->translateOrNew(app()->getLocale())->name ?? $currentCategory->name }}
                                <a href="{{ route('category.show') }}" class="ms-2 text-success"><i class="fas fa-times-circle"></i></a>
                            </div>
                        @else
                            <span class="text-muted fw-medium">{{ __('Categories') }}</span>
                        @endif
                    </div>
                    <span class="text-muted small">
                        {{ __('Showing') }} <strong>{{ $products->count() }} </strong> {{ __('products') }}
                    </span>
                </div>

                <div class="row g-4">
                    @forelse($products as $product)
                        <div class="col-md-6 col-xl-4">
                            <div class="p-card">
                                {{-- CLICKABLE IMAGE --}}
                                <div class="p-img-box">
                                    <a href="{{ route('product.detail', $product->slug) }}">
                                        <img src="{{ asset($product->image) }}"
                                             alt="{{ $product->translateOrNew(app()->getLocale())->title ?? $product->title }}"
                                             onerror="this.src='https://placehold.co/600x400?text=No+Image'">
                                    </a>
                                </div>
                                <div class="p-4 d-flex flex-column flex-grow-1">
                                    <div class="mb-2">
                                        <span class="badge bg-success bg-opacity-10 text-success uppercase" style="font-size: 10px;">
                                            {{ $product->category ? ($product->category->translateOrNew(app()->getLocale())->name ?? $product->category->name) : __('Categories') }}
                                        </span>
                                    </div>
                                    {{-- CLICKABLE TITLE --}}
                                    <h6 class="fw-bold mb-2">
                                        <a href="{{ route('product.detail', $product->slug) }}" class="p-title-link">
                                            {{ $product->translateOrNew(app()->getLocale())->title ?? $product->title }}
                                        </a>
                                    </h6>
                                    <p class="text-muted small mb-3">
                                        {{ Str::limit(strip_tags($product->translateOrNew(app()->getLocale())->long_description ?? $product->long_description), 70) }}
                                    </p>
                                    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-success d-none">
                                            {{ $product->price > 0 ? '£' . number_format($product->price, 2) : __('shop.price_on_request') }}
                                        </span>
                                        <a href="{{ route('product.detail', $product->slug) }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
                                            {{ __('header.inquire_now') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5 bg-white rounded-3 shadow-sm">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('shop.no_products_found') }}</h5>
                                <a href="{{ route('category.show') }}" class="btn btn-success mt-3">{{ __('shop.back_to_all') }}</a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</main>
@endsection