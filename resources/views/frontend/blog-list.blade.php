@extends('frontend.layouts.master')

@section('content')
<style>
    :root { --pfc-green: #00a651; }

    /* --- Hero --- */
    .blog-hero {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1586771107445-d3ca888129ce?auto=format&fit=crop&w=1600&q=80');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 60px 0;
        margin-bottom: 50px;
    }

    /* --- Blog Cards --- */
    .blog-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #eef2f6;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .blog-img-wrapper {
        height: 220px;
        overflow: hidden;
        background: #f8f9fa;
        position: relative;
    }

    .blog-img-wrapper a {
        display: block;
        width: 100%;
        height: 100%;
    }

    .blog-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .blog-card:hover .blog-img-wrapper img {
        transform: scale(1.05);
    }

    /* Date badge overlay */
    .blog-date-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--pfc-green);
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1.3;
        text-align: center;
        z-index: 2;
    }

    .blog-date-badge .day {
        display: block;
        font-size: 1.1rem;
        font-weight: 700;
        line-height: 1;
    }

    .blog-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .blog-meta {
        display: flex;
        gap: 15px;
        margin-bottom: 12px;
        font-size: 0.8rem;
        color: #888;
    }

    .blog-meta i {
        color: var(--pfc-green);
        margin-right: 4px;
    }

    .blog-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #222;
        text-decoration: none;
        display: block;
        margin-bottom: 10px;
        line-height: 1.4;
        transition: color 0.2s;
    }

    .blog-title:hover {
        color: var(--pfc-green);
    }

    .blog-excerpt {
        font-size: 0.875rem;
        color: #666;
        line-height: 1.6;
        margin-bottom: 15px;
        flex-grow: 1;
    }

    .read-more {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--pfc-green);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: gap 0.3s;
    }

    .read-more:hover {
        gap: 10px;
        color: #008c44;
    }

    /* Author avatar */
    .blog-author {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-top: 15px;
        margin-top: auto;
        border-top: 1px solid #f0f0f0;
    }

    .author-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--pfc-green);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.75rem;
    }

    .author-info .name {
        font-size: 0.8rem;
        font-weight: 600;
        color: #333;
    }

    .author-info .label {
        font-size: 0.7rem;
        color: #999;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    /* Pagination override */
    .blog-pagination .page-link {
        color: var(--pfc-green);
        border-color: #eef2f6;
        padding: 10px 16px;
        margin: 0 3px;
        border-radius: 8px;
        font-weight: 500;
    }

    .blog-pagination .page-item.active .page-link {
        background: var(--pfc-green);
        border-color: var(--pfc-green);
        color: white;
    }

    .blog-pagination .page-link:hover {
        background: #f0fdf4;
        border-color: var(--pfc-green);
    }

    /* Top info bar */
    .blog-top-bar {
        background: white;
        padding: 20px 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    @media (max-width: 768px) {
        .blog-img-wrapper { height: 180px; }
        .blog-hero { padding: 40px 0; }
    }
</style>

<header class="blog-hero">
    <div class="container text-center text-md-start">
        <h1 class="fw-bold display-5">{{ __('blog.our_blog') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 justify-content-center justify-content-md-start">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}" class="text-white opacity-75 text-decoration-none">{{ __('header.home') }}</a>
                </li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ __('blog.our_blog') }}</li>
            </ol>
        </nav>
    </div>
</header>

<main class="pb-5">
    <div class="container">

        {{-- Top Info Bar --}}
        <div class="blog-top-bar d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="text-muted fw-medium">{{ __('Showing') }} <strong>{{ $blogs->count() }}</strong> {{ __('articles') }}</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="far fa-newspaper text-muted"></i>
                <span class="text-muted small">{{ __('blog.stay_updated') }}</span>
            </div>
        </div>

        {{-- Blog Cards Grid --}}
        <div class="row g-4">
            @forelse($blogs as $blog)
                <div class="col-lg-4 col-md-6">
                    <div class="blog-card">
                        {{-- Clickable Image with Date Badge --}}
                        <div class="blog-img-wrapper">
                            <a href="{{ route('blog.show', $blog->slug) }}">
                                <img src="{{ $blog->image ? asset($blog->image) : 'https://placehold.co/600x400?text=No+Image' }}" 
                                     alt="{{ $blog->title }}">
                            </a>
                            <div class="blog-date-badge">
                                <span class="day">{{ \Carbon\Carbon::parse($blog->published_at)->format('d') }}</span>
                                {{ \Carbon\Carbon::parse($blog->published_at)->format('M Y') }}
                            </div>
                        </div>

                        <div class="blog-content">
                            {{-- Meta --}}
                            <div class="blog-meta">
                                <span><i class="far fa-user"></i> {{ $blog->author_name }}</span>
                                <span><i class="far fa-clock"></i> {{ Str::limit($blog->excerpt, 0) == '' ? '' : \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') }}</span>
                            </div>

                            {{-- Clickable Title --}}
                            <a href="{{ route('blog.show', $blog->slug) }}" class="blog-title">
                                {{ $blog->title }}
                            </a>

                            {{-- Excerpt --}}
                            <p class="blog-excerpt">
                                {{ Str::limit(strip_tags($blog->excerpt), 90) }}
                            </p>

                            {{-- Read More --}}
                            <a href="{{ route('blog.show', $blog->slug) }}" class="read-more">
                                {{ __('Read More') }} <i class="fas fa-arrow-right small"></i>
                            </a>

                            {{-- Author Footer --}}
                            <div class="blog-author">
                                <div class="author-avatar">
                                    {{ substr($blog->author_name ?? 'A', 0, 1) }}
                                </div>
                                <div class="author-info">
                                    <div class="name">{{ $blog->author_name }}</div>
                                    <div class="label">{{ __('Author') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-newspaper fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                        <h5 class="text-muted mb-2">{{ __('blog.no_articles_found') }}</h5>
                        <p class="text-muted small mb-4">{{ __('blog.check_back_later') }}</p>
                        <a href="{{ url('/') }}" class="btn btn-success">{{ __('header.home') }}</a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($blogs->hasPages())
            <div class="mt-5 d-flex justify-content-center">
                {{ $blogs->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif

    </div>
</main>

{{-- Add pagination JS to apply custom class --}}
@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pagination = document.querySelector('.pagination');
        if (pagination) {
            pagination.classList.add('blog-pagination');
        }
    });
</script>
@endpush

@endsection