@extends('frontend.layouts.master')

@section('content')

<style>
    /* --- Blog Detail Specific Styles --- */
.blog-header {
    padding: 60px 0;
    background-color: #f8fdfa; /* Matching your blog card background */
}

.blog-featured-img {
    width: 100%;
    height: auto;
    max-height: 500px;
    object-fit: cover;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin-top: -80px; /* Overlaps the header slightly for a modern look */
}

.article-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #444;
}

.article-content h2, .article-content h3 {
    color: #00a651;
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.blog-sidebar-card {
    border: none;
    background-color: #f8fdfa;
    border-radius: 15px;
    padding: 25px;
    position: sticky;
    top: 20px;
}

.share-buttons .btn {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    background: #fff;
    border: 1px solid #eee;
    color: #00a651;
}

.share-buttons .btn:hover {
    background: #00a651;
    color: #fff;
}

.hover-green:hover { color: #00a651 !important; }

/* Responsive adjustments */
@media (max-width: 768px) {
    .blog-featured-img {
        margin-top: 0;
        border-radius: 10px;
    }
    .blog-header {
        padding: 40px 0;
    }
}
</style>




<header class="blog-header">
    <div class="container text-center">
        <div class="blog-meta mb-3 justify-content-center">
            <span><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') }}</span>
            <span><i class="far fa-user"></i> {{ $blog->author_name }}</span>
            {{-- Using 'tag' if you added it to your table, otherwise using a generic label --}}
            <span class="badge bg-success" style="background-color: #00a651 !important;">{{ __('Article') }}</span>
        </div>
        <h1 class="display-4 fw-bold mb-4" style="color: #00a651;">{{ $blog->title }}</h1>
    </div>
</header>

<section class="pb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-5">
                <img src="{{ $blog->image ? asset($blog->image) : 'https://via.placeholder.com/1200x500' }}" 
                     class="blog-featured-img" alt="{{ $blog->title }}">
            </div>

            <div class="col-lg-8">
                <article class="article-content">
                    {{-- Excerpt as Lead text --}}
                    <p class="lead fw-bold">
                        {{ $blog->excerpt }}
                    </p>
                    
                    {{-- Full Content from Summernote (Raw HTML) --}}
                    <div class="mt-4">
                        {!! $blog->description !!}
                    </div>
                </article>

                <div class="d-flex align-items-center gap-3 mt-5 py-4 border-top border-bottom">
                    <span class="fw-bold">Share this article:</span>
                    <div class="share-buttons">
                        {{-- Social Sharing Links --}}
                        <div class="share-buttons">
                            {{-- Social Sharing Links --}}
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="btn">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($blog->title) }}" target="_blank" class="btn">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}" target="_blank" class="btn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mt-5 mt-lg-0">
                <aside class="blog-sidebar-card">
                    <h4 class="fw-bold mb-3" style="color: #00a651;">{{ __('About the Author') }}</h4>
                    <p class="small text-muted mb-4">{{ $blog->author_name }} {{ __('is a contributor to the PFC Agri blog, sharing insights on modern farming and sustainable solutions.') }}</p>
                    
                    <hr>
                    
                    <h4 class="fw-bold mb-3" style="color: #00a651;">{{ __('Recent Posts') }}</h4>
                    <ul class="list-unstyled">
                        @foreach($relatedPosts as $post)
                            <li class="mb-3">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark hover-green">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset($post->image) }}" width="50" height="50" class="rounded me-2" style="object-fit: cover;">
                                        <span class="small fw-bold">{{ Str::limit($post->title, 40) }}</span>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-4 p-3 rounded" style="background-color: #00a651; color: #fff;">
                        <h5>{{ __('Need Expert Advice?') }}</h5>
                        <p class="small">{{ __('Contact our team for a free consultation on your storage needs.') }}</p>
                        <a href="{{ route('contact') }}" class="btn btn-light btn-sm fw-bold">{{ __('Contact Us') }}</a>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>

@endsection