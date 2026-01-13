@extends('frontend.layouts.master')

@section('content')


<style>
    /* --- About PFC Agri Solutions Header --- */
    .pfc-about-header {
        background-color: var(--pfc-green);
        color: white;
        padding: 80px 0;
        text-align: center;
    }

    /* --- Our Story Section --- */
    .our-story-section {
        padding: 100px 0;
        background-color: #fff;
    }

    .story-image-wrapper {
        position: relative;
    }

    .story-image-wrapper img {
        width: 100%;
        border-radius: 15px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .years-badge {
        position: absolute;
        bottom: 20px;
        left: -30px;
        background-color: var(--pfc-green);
        color: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 166, 81, 0.3);
        z-index: 2;
    }

    @media (max-width: 991px) {
        .years-badge { left: 20px; }
    }

    /* --- Core Values Section --- */
    .values-section {
        padding: 80px 0 120px;
        background-color: #f9fbf9;
    }

    .value-card {
        background: white;
        border: none;
        border-radius: 20px;
        padding: 40px 30px;
        text-align: left;
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .value-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 166, 81, 0.1);
    }

    .value-icon-box {
        width: 60px;
        height: 60px;
        background: #f0fdf4;
        color: var(--pfc-green);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 25px;
    }

</style>


<section class="pfc-about-header">
    <div class="container">
        <h1 class="fw-bold mb-3">About PFC Agri Solutions</h1>
        <p class="lead opacity-90 mx-auto" style="max-width: 750px;">
            A family farming business delivering innovative technological solutions to the agricultural industry since 1985.
        </p>
    </div>
</section>

<section class="our-story-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <p class="section-tag mb-1">OUR STORY</p>
                <h2 class="fw-bold mb-4" style="color: var(--pfc-green);">Rooted in Farming, Growing with Innovation</h2>
                <p class="text-muted">PFC Agri Solutions began as a small family farm in the heart of the British countryside. Over three generations, we've grown from traditional farming into a leading supplier of innovative agricultural solutions.</p>
                <p class="text-muted">Our journey has always been guided by a simple principle: we only recommend products that we would use on our own farm. This hands-on approach means we truly understand the challenges our customers face and can provide solutions that work in real-world conditions.</p>
                <p class="text-muted">Today, we're proud to combine our deep agricultural roots with cutting-edge technology, offering a comprehensive range of products from slurry management to field preparation equipment.</p>
            </div>
            <div class="col-lg-6">
                <div class="story-image-wrapper">
                    <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1000&q=80" alt="Sunset over farm">
                    <div class="years-badge">
                        <h3 class="fw-bold mb-0">35+</h3>
                        <p class="small mb-0">Years of Excellence</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="values-section text-center">
    <div class="container">
        <p class="section-tag mb-1">WHAT DRIVES US</p>
        <h2 class="fw-bold mb-5" style="color: var(--pfc-green);">Our Core Values</h2>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="value-card">
                    <div class="value-icon-box"><i class="fas fa-users"></i></div>
                    <h5 class="fw-bold">Family Values</h5>
                    <p class="small text-muted mb-0">Three generations of farming expertise passed down, ensuring authentic understanding of agricultural challenges.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="value-card">
                    <div class="value-icon-box"><i class="fas fa-ribbon"></i></div>
                    <h5 class="fw-bold">Quality First</h5>
                    <p class="small text-muted mb-0">We only supply products we would use ourselves. Every item is tested and proven in real farming conditions.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="value-card">
                    <div class="value-icon-box"><i class="fas fa-leaf"></i></div>
                    <h5 class="fw-bold">Sustainability</h5>
                    <p class="small text-muted mb-0">Committed to environmentally responsible solutions that protect our land for future generations.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="value-card">
                    <div class="value-icon-box"><i class="fas fa-bullseye"></i></div>
                    <h5 class="fw-bold">Innovation</h5>
                    <p class="small text-muted mb-0">Constantly seeking and developing new technologies to improve farming efficiency and outcomes.</p>
                </div>
            </div>
        </div>
    </div>
</section>


<style>
    /* --- Meet Our Team Section --- */
    .team-section {
        padding: 80px 0;
        background-color: #fff;
    }

    .team-card {
        background: #f9fbf9;
        border: none;
        border-radius: 20px;
        padding: 40px 25px;
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
    }

    .team-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0, 166, 81, 0.1);
    }

    .team-img-wrapper {
        width: 130px;
        height: 130px;
        margin: 0 auto 25px;
        border-radius: 50%;
        overflow: hidden;
        border: 5px solid white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .team-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .team-role {
        color: var(--pfc-green);
        font-size: 0.85rem;
        font-weight: 700;
        text-uppercase;
        margin-bottom: 15px;
    }

    .team-contact-link {
        color: #555;
        text-decoration: none;
        font-size: 0.85rem;
        display: block;
        margin-top: 8px;
        transition: 0.2s;
    }

    .team-contact-link:hover {
        color: var(--pfc-green);
    }

    /* --- Get In Touch Section (Green Background) --- */
    .get-in-touch-section {
        padding: 100px 0;
        background-color: #00a651;
        color: white;
    }

    .contact-method-card {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 40px 20px;
        text-align: center;
        height: 100%;
        transition: 0.3s;
    }

    .contact-method-card:hover {
        background: rgba(255, 255, 255, 0.15);
    }

    .contact-icon-circle {
        width: 60px;
        height: 60px;
        background: #76ce81;
        color: #00a651;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 1.5rem;
    }
</style>

<section class="team-section">
    <div class="container text-center">
        <p class="section-tag mb-1">THE PEOPLE BEHIND PFC</p>
        <h2 class="fw-bold mb-3" style="color: var(--pfc-green);">Meet Our Team</h2>
        <p class="text-muted mx-auto mb-5" style="max-width: 600px;">Our dedicated team combines farming experience with technical expertise to serve you better.</p>
        
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="team-card">
                    <div class="team-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=300&q=80" alt="James Fletcher">
                    </div>
                    <h5 class="fw-bold mb-1">James Fletcher</h5>
                    <div class="team-role">Managing Director</div>
                    <p class="small text-muted mb-4">Third generation farmer with 30 years of industry experience. James leads our strategic direction and customer relationships.</p>
                    <a href="tel:+441234567890" class="team-contact-link"><i class="fas fa-phone me-2 text-success"></i> +44 1234 567890</a>
                    <a href="mailto:james@pfcagri.co.uk" class="team-contact-link"><i class="fas fa-envelope me-2 text-success"></i> james@pfcagri.co.uk</a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="team-card">
                    <div class="team-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=300&q=80" alt="Sarah Fletcher">
                    </div>
                    <h5 class="fw-bold mb-1">Sarah Fletcher</h5>
                    <div class="team-role">Operations Director</div>
                    <p class="small text-muted mb-4">With a background in agricultural engineering, Sarah ensures smooth operations and product quality across all departments.</p>
                    <a href="tel:+441234567891" class="team-contact-link"><i class="fas fa-phone me-2 text-success"></i> +44 1234 567891</a>
                    <a href="mailto:sarah@pfcagri.co.uk" class="team-contact-link"><i class="fas fa-envelope me-2 text-success"></i> sarah@pfcagri.co.uk</a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="team-card">
                    <div class="team-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80" alt="Tom Williams">
                    </div>
                    <h5 class="fw-bold mb-1">Tom Williams</h5>
                    <div class="team-role">Technical Sales Manager</div>
                    <p class="small text-muted mb-4">Tom brings extensive knowledge of agricultural machinery and is dedicated to finding the right solutions for our customers.</p>
                    <a href="tel:+441234567892" class="team-contact-link"><i class="fas fa-phone me-2 text-success"></i> +44 1234 567892</a>
                    <a href="mailto:tom@pfcagri.co.uk" class="team-contact-link"><i class="fas fa-envelope me-2 text-success"></i> tom@pfcagri.co.uk</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="get-in-touch-section">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Get In Touch</h2>
        <p class="opacity-75 mx-auto mb-5" style="max-width: 600px;">We'd love to hear from you. Reach out to discuss your agricultural needs.</p>
        
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="contact-method-card">
                    <div class="contact-icon-circle"><i class="fas fa-phone"></i></div>
                    <h5 class="fw-bold mb-2">Call Us</h5>
                    <p class="mb-0 opacity-90">+44 (0) 1234 567890</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact-method-card">
                    <div class="contact-icon-circle"><i class="fas fa-envelope"></i></div>
                    <h5 class="fw-bold mb-2">Email Us</h5>
                    <p class="mb-0 opacity-90">info@pfcagri.co.uk</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact-method-card">
                    <div class="contact-icon-circle"><i class="fas fa-map-marker-alt"></i></div>
                    <h5 class="fw-bold mb-2">Visit Us</h5>
                    <p class="small mb-0 opacity-90">PFC Agri Solutions<br>Farm Road, Rural County<br>United Kingdom</p>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection

@section('script')


@endsection