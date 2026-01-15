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
            <span><i class="far fa-calendar-alt"></i> Jan 5, 2026</span>
            <span><i class="far fa-user"></i> John Smith</span>
            <span class="badge bg-success" style="background-color: #00a651 !important;">Sustainability</span>
        </div>
        <h1 class="display-4 fw-bold mb-4" style="color: #00a651;">Maximizing Slurry Storage Efficiency</h1>
    </div>
</header>

<section class="pb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-5">
                <img src="https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?auto=format&fit=crop&w=1200&q=80" class="blog-featured-img" alt="Slurry Efficiency">
            </div>

            <div class="col-lg-8">
                <article class="article-content">
                    <p class="lead">
                        Proper slurry storage is more than just a regulatory requirement; it’s a vital component of a sustainable and profitable modern farming operation. 
                    </p>
                    
                    <p>In 2026, the focus on environmental impact and nutrient management has never been higher. Efficient storage ensures that valuable nutrients remain available for crops while preventing harmful runoff into local waterways.</p>

                    <h2>The Role of Modern Lagoon Liners</h2>
                    <p>One of the most significant advancements in storage technology is the high-density polyethylene (HDPE) liner. These liners provide an impermeable barrier that prevents seepage and protects groundwater. Unlike traditional clay bases, modern liners are resistant to UV rays and chemical breakdown.</p>
                    
                    <blockquote class="border-start border-4 ps-4 my-4 fs-5 italic" style="border-color: #00a651 !important; color: #555;">
                        "Effective slurry management can reduce synthetic fertilizer costs by up to 30% by preserving organic nitrogen levels."
                    </blockquote>

                    <h3>Key Benefits of Maintenance</h3>
                    <ul>
                        <li>Reduction in maintenance labor costs.</li>
                        <li>Compliance with the latest environmental regulations.</li>
                        <li>Improved safety for farm staff and livestock.</li>
                    </ul>

                    <p>By investing in quality storage solutions now, farmers can ensure their operations remain resilient against future climate challenges and regulatory changes.</p>
                </article>

                <div class="d-flex align-items-center gap-3 mt-5 py-4 border-top border-bottom">
                    <span class="fw-bold">Share this article:</span>
                    <div class="share-buttons">
                        <a href="#" class="btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mt-5 mt-lg-0">
                <aside class="blog-sidebar-card">
                    <h4 class="fw-bold mb-3" style="color: #00a651;">About the Author</h4>
                    <p class="small text-muted mb-4">John Smith is a senior agricultural consultant with 15 years of experience in sustainable waste management and farm infrastructure.</p>
                    
                    <hr>
                    
                    <h4 class="fw-bold mb-3" style="color: #00a651;">Related Topics</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-dark hover-green">• Waste Management</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-dark">• Soil Health</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-dark">• Farm Tech 2026</a></li>
                    </ul>

                    <div class="mt-4 p-3 rounded" style="background-color: #00a651; color: #fff;">
                        <h5>Need Expert Advice?</h5>
                        <p class="small">Contact our team for a free consultation on your storage needs.</p>
                        <a href="#" class="btn btn-light btn-sm fw-bold">Contact Us</a>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>




@endsection

@section('script')


@endsection