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
            <p class="section-tag mb-1">What we offer</p>
            <h2 class="fw-bold mb-3" style="color: #00a651;">Our Product Categories</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">Comprehensive range of agricultural solutions designed to enhance your farming operations</p>
        </div>

        <div class="container">
            <div class="row g-4">


                <div class="col-md-6">
                    <div class="card category-card">
                        <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" class="card-img h-100" style="object-fit: cover;" alt="Slurry Management">
                        <div class="card-img-overlay">
                            <h3>Slurry Management</h3>
                            <p class="small opacity-75">Lagoon liners, slurry bags and complete slurry handling solutions</p>
                            <div class="mb-3">
                                <span class="tag-pill">Lagoon Liners</span>
                                <span class="tag-pill">Slurry Bags</span>
                            </div>
                            <a href="#" class="text-white text-decoration-none fw-bold">View Products &rarr;</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card category-card">
                        <img src="https://images.unsplash.com/photo-1533240332313-0db49b459ad6?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" class="card-img h-100" style="object-fit: cover;" alt="Wet Bale">
                        <div class="card-img-overlay">
                            <h3>Wet Bale Management</h3>
                            <p class="small opacity-75">Complete wet bale preservation systems and equipment</p>
                            <div class="mb-3">
                                <span class="tag-pill">Applicators</span>
                                <span class="tag-pill">Moisture Meters</span>
                                <span class="tag-pill">Acid Preservative</span>
                            </div>
                            <a href="#" class="text-white text-decoration-none fw-bold">View Products &rarr;</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card category-card">
                        <img src="https://images.unsplash.com/photo-1589923188900-85dae523342b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" class="card-img h-100" style="object-fit: cover;" alt="Field Prep">
                        <div class="card-img-overlay">
                            <h3>Field Preparation</h3>
                            <p class="small opacity-75">Professional cultivation and tillage equipment</p>
                            <div class="mb-3">
                                <span class="tag-pill">Cultivators</span>
                                <span class="tag-pill">Subsoilers</span>
                                <span class="tag-pill">Drills</span>
                            </div>
                            <a href="#" class="text-white text-decoration-none fw-bold">View Products &rarr;</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card category-card">
                        <img src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" class="card-img h-100" style="object-fit: cover;" alt="Woodland">
                        <div class="card-img-overlay">
                            <h3>Woodland Management</h3>
                            <p class="small opacity-75">Professional forestry and woodland care tools</p>
                            <div class="mb-3">
                                <span class="tag-pill">Saws</span>
                                <span class="tag-pill">Trimmer Bars</span>
                            </div>
                            <a href="#" class="text-white text-decoration-none fw-bold">View Products &rarr;</a>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
    </section>

    <section class="about-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="about-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1592982537447-7440770cbfc9?auto=format&fit=crop&w=800&q=80" alt="Farming Background" class="main-about-img">
                        <div class="experience-badge">
                            <div class="badge-number">35+</div>
                            <div>
                                <div class="fw-bold mb-0">Years</div>
                                <small class="text-muted">In Business</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <p class="section-tag mb-2">About Us</p>
                    <h2 class="about-title mb-4">A Family Business Built on Trust & Innovation</h2>
                    <p class="text-muted mb-5">
                        PFC Agri Solutions is a family-run farming business that combines generations of agricultural knowledge with innovative technological solutions. We understand farming because we are farmers ourselves, and we're committed to providing products that make a real difference to your operation.
                    </p>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="feature-box">
                                <div class="feature-icon"><i class="fas fa-users"></i></div>
                                <div class="feature-content">
                                    <h6>Family Owned</h6>
                                    <p>Three generations of farming expertise</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="feature-box">
                                <div class="feature-icon"><i class="fas fa-award"></i></div>
                                <div class="feature-content">
                                    <h6>Quality Assured</h6>
                                    <p>Premium products with proven performance</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="feature-box">
                                <div class="feature-icon"><i class="fas fa-leaf"></i></div>
                                <div class="feature-content">
                                    <h6>Sustainable</h6>
                                    <p>Eco-conscious farming solutions</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="feature-box">
                                <div class="feature-icon"><i class="fas fa-tools"></i></div>
                                <div class="feature-content">
                                    <h6>Full Support</h6>
                                    <p>Expert advice and after-sales service</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="#" class="btn btn-learn-more">Learn More About Us &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="blog-section">
        <div class="container">
            <div class="text-center mb-5">
                <p class="section-tag mb-1">Latest Insights</p>
                <h2 class="fw-bold mb-3" style="color: #00a651;">From Our Blog</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">Stay updated with the latest farming tips, product news, and industry insights.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="blog-card">
                        <div class="blog-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?auto=format&fit=crop&w=600&q=80" alt="Slurry Efficiency">
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span><i class="far fa-calendar-alt"></i> Jan 5, 2026</span>
                                <span><i class="far fa-user"></i> John Smith</span>
                            </div>
                            <a href="#" class="blog-title">Maximizing Slurry Storage Efficiency</a>
                            <p class="blog-excerpt">Learn how modern lagoon liners can reduce maintenance costs and improve farm sustainability.</p>
                            <a href="#" class="read-more">Read More &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="blog-card">
                        <div class="blog-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=600&q=80" alt="Moisture Meters">
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span><i class="far fa-calendar-alt"></i> Jan 3, 2026</span>
                                <span><i class="far fa-user"></i> Sarah Johnson</span>
                            </div>
                            <a href="#" class="blog-title">New Product Launch: Advanced Moisture Meters</a>
                            <p class="blog-excerpt">Introducing our latest technology for precise hay and grain moisture measurement systems.</p>
                            <a href="#" class="read-more">Read More &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="blog-card">
                        <div class="blog-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1592982537447-7440770cbfc9?auto=format&fit=crop&w=600&q=80" alt="Sustainable Farming">
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span><i class="far fa-calendar-alt"></i> Jan 1, 2026</span>
                                <span><i class="far fa-user"></i> Michael Brown</span>
                            </div>
                            <a href="#" class="blog-title">Sustainable Farming Practices for 2026</a>
                            <p class="blog-excerpt">Industry trends and innovations shaping the future of agriculture and local farming.</p>
                            <a href="#" class="read-more">Read More &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="dealer-section">
        <div class="container">
            <div class="text-center mb-5">
                <p class="section-tag mb-1">Our Partners</p>
                <h2 class="fw-bold mb-3" style="color: #00a651;">Authorized Dealer Network</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">Find a trusted PFC Agri Solutions dealer near you for expert service and support.</p>
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
            <p class="section-tag mb-1">RECOGNITION</p>
            <h2 class="fw-bold mb-3" style="color: #00a651;">Awards & Achievements</h2>
            <p class="text-muted mx-auto mb-5" style="max-width: 600px;">Our commitment to excellence has been recognized by industry leaders and organizations.</p>
            
            <div class="row g-4 text-start">
                <div class="col-md-4">
                    <div class="award-card">
                        <div class="award-icon-circle"><i class="fas fa-trophy"></i></div>
                        <h5 class="fw-bold">Agricultural Innovation Award</h5>
                        <p class="small text-muted mb-2">UK Farming Association • 2025</p>
                        <span class="tag-pill mb-3">Innovation</span>
                        <p class="small text-muted mt-2">Recognized for outstanding innovation in slurry management technology.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="award-card">
                        <div class="award-icon-circle"><i class="fas fa-leaf"></i></div>
                        <h5 class="fw-bold">Sustainability Champion</h5>
                        <p class="small text-muted mb-2">Green Agriculture Council • 2024</p>
                        <span class="tag-pill mb-3">Sustainability</span>
                        <p class="small text-muted mt-2">Awarded for commitment to environmentally sustainable farming solutions.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="award-card">
                        <div class="award-icon-circle"><i class="fas fa-medal"></i></div>
                        <h5 class="fw-bold">Best Agricultural Supplier</h5>
                        <p class="small text-muted mb-2">Farmers Weekly • 2024</p>
                        <span class="tag-pill mb-3">Service Excellence</span>
                        <p class="small text-muted mt-2">Voted best supplier by UK farmers for product quality and customer service.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <h2 class="fw-bold mb-3" style="color: #00a651;">Ready to Transform Your Farming Operation?</h2>
                    <p class="text-muted mb-4">Get in touch with our team to discuss your requirements. We're here to help you find the right solutions for your agricultural needs.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-success btn-lg px-4 rounded-1" style="background-color: var(--pfc-green);">Inquire Now &rarr;</a>
                        <a href="#" class="btn btn-outline-success btn-lg px-4 rounded-1">Browse Products</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="contact-info-card">
                                <div class="contact-icon-box"><i class="fas fa-phone"></i></div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Call Us</h6>
                                    <p class="mb-0 text-muted">+44 (0) 1234 567890</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="contact-info-card">
                                <div class="contact-icon-box"><i class="fas fa-envelope"></i></div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Email Us</h6>
                                    <p class="mb-0 text-muted">info@pfcagri.co.uk</p>
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