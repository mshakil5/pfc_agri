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
    
    .p-img-box { height: 200px; overflow: hidden; background: #f8f9fa; }
    .p-img-box img { width: 100%; height: 100%; object-fit: cover; }

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
            {{ $currentCategory ? $currentCategory->name : 'Our Product Range' }}
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 justify-content-center justify-content-md-start">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white opacity-75 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Shop</li>
            </ol>
        </nav>
    </div>
</header>

<main class="pb-5">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <div class="category-sidebar">
                    <h6 class="sidebar-title text-uppercase small">Categories</h6>
                    
                    <a href="{{ route('category.show') }}" class="main-cat {{ !$currentCategory ? 'active' : '' }}">
                        <span>All Products</span>
                        <i class="fas fa-chevron-right small opacity-50"></i>
                    </a>
                    
                    @foreach($categories as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}" 
                       class="main-cat {{ ($currentCategory && $currentCategory->id == $cat->id) ? 'active' : '' }}">
                        <span>{{ $cat->name }}</span>
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
                                Category: {{ $currentCategory->name }} 
                                <a href="{{ route('category.show') }}" class="ms-2 text-success"><i class="fas fa-times-circle"></i></a>
                            </div>
                        @else
                            <span class="text-muted fw-medium">All Categories</span>
                        @endif
                    </div>
                    <span class="text-muted small">Showing <strong>{{ $products->count() }}</strong> of {{ $products->total() }} results</span>
                </div>

                <div class="row g-4">
                    @forelse($products as $product)
                    <div class="col-md-6 col-xl-4">
                        <div class="p-card">
                            <div class="p-img-box">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->title }}" onerror="this.src='https://placehold.co/600x400?text=No+Image'">
                            </div>
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <div class="mb-2">
                                    <span class="badge bg-success bg-opacity-10 text-success uppercase" style="font-size: 10px;">
                                        {{ $product->category->name ?? 'Agriculture' }}
                                    </span>
                                </div>
                                <h6 class="fw-bold mb-2">{{ $product->title }}</h6>
                                <p class="text-muted small mb-3">
                                    {{ Str::limit(strip_tags($product->long_description), 70) }}
                                </p>
                                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-success">
                                        {{ $product->price > 0 ? '£' . number_format($product->price, 2) : 'Price on Request' }}
                                    </span>
                                    <a href="{{ route('product.detail') }}" class="btn btn-outline-success btn-sm rounded-pill px-3">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5 bg-white rounded-3 shadow-sm">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No products found in this category.</h5>
                            <a href="{{ route('category.show') }}" class="btn btn-success mt-3">Back to All Products</a>
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