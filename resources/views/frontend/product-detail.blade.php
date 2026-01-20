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
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Products</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Wet Bale Management</a></li>
                    <li class="breadcrumb-item active fw-bold text-success">Pro Acid Applicator</li>
                </ol>
            </nav>
        </div>
    </section>

    <main class="py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="main-product-img">
                        <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80" class="img-fluid" id="mainImg" alt="Pro Acid Applicator">
                    </div>
                    <div class="thumb-grid">
                        <div class="thumb-item active"><img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=300" onclick="document.getElementById('mainImg').src=this.src"></div>
                        <div class="thumb-item"><img src="https://images.unsplash.com/photo-1622383529357-37421312f48f?auto=format&fit=crop&w=300" onclick="document.getElementById('mainImg').src=this.src"></div>
                        <div class="thumb-item"><img src="https://images.unsplash.com/photo-1592982537447-7440770cbfc9?auto=format&fit=crop&w=300" onclick="document.getElementById('mainImg').src=this.src"></div>
                        <div class="thumb-item"><img src="https://images.unsplash.com/photo-1589923188900-85dae523342b?auto=format&fit=crop&w=300" onclick="document.getElementById('mainImg').src=this.src"></div>
                    </div>

                    <div class="mt-5">
                        <ul class="nav nav-pills mb-4 nav-pills-custom" id="pills-tab" role="tablist">
                            <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#desc">Overview</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#specs">Technical Specs</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#docs">Downloads</button></li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="desc">
                                <h4 class="fw-bold mb-3">Maximize Forage Quality</h4>
                                <p>The Pro Acid Applicator is engineered for the modern farmer who refuses to compromise on silage quality. Utilizing high-precision sensors, the system automatically adjusts acid application rates based on real-time moisture data.</p>
                                <div class="row mt-4 g-4">
                                    <div class="col-md-6">
                                        <div class="feature-icon-box"><i class="fas fa-microchip"></i></div>
                                        <h6>Smart Flow Control</h6>
                                        <p class="small text-muted">Adjusts dosage dynamically between 0.5L to 10L per ton.</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="feature-icon-box"><i class="fas fa-shield-alt"></i></div>
                                        <h6>Corrosion Resistant</h6>
                                        <p class="small text-muted">Built with high-grade stainless steel and acid-proof seals.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="specs">
                                <table class="table table-bordered tech-table">
                                    <tr><td>Compatible Balers</td><td>Most Round & Square Models</td></tr>
                                    <tr><td>Tank Capacity</td><td>100L / 200L / 400L Options</td></tr>
                                    <tr><td>Pump Type</td><td>12V High-Pressure Diaphragm</td></tr>
                                    <tr><td>Control System</td><td>Cab-mounted LCD Interface</td></tr>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="docs">
                                <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between">
                                        <span><i class="far fa-file-pdf me-2 text-danger"></i> Installation Manual.pdf</span>
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between">
                                        <span><i class="far fa-file-pdf me-2 text-danger"></i> Product Brochure 2026.pdf</span>
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="ps-lg-4">
                        <span class="badge-category">Applicators</span>
                        <h1 class="product-title">Pro Acid Applicator System</h1>
                        <div class="price-tag">Price on Request</div>
                        
                        <p class="text-muted mb-4">A complete precision treatment system designed to apply preservatives accurately to baled crops, ensuring minimal spoilage and maximum nutrient retention.</p>

                        <div class="spec-shortcut">
                            <div class="shortcut-item"><i class="fas fa-check-circle"></i><span>Plug & Play</span></div>
                            <div class="shortcut-item"><i class="fas fa-check-circle"></i><span>Universal Fit</span></div>
                            <div class="shortcut-item"><i class="fas fa-check-circle"></i><span>GPS Ready</span></div>
                            <div class="shortcut-item"><i class="fas fa-check-circle"></i><span>2 Year Warranty</span></div>
                        </div>

                        <div class="inquiry-card shadow-lg">
                            <h5 class="fw-bold mb-3">Request a Quote</h5>
                            <p class="small opacity-75 mb-4">Our specialists will contact you within 24 hours with a custom quote and configuration advice.</p>
                            
                            <form>
                                <div class="mb-3">
                                    <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="Full Name">
                                </div>
                                <div class="mb-3">
                                    <input type="email" class="form-control bg-dark text-white border-secondary" placeholder="Email Address">
                                </div>
                                <div class="mb-4">
                                    <select class="form-select bg-dark text-white border-secondary">
                                        <option selected>Select Baler Model</option>
                                        <option>John Deere</option>
                                        <option>New Holland</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                                <button class="btn-pfc-lg">Submit Inquiry <i class="fas fa-paper-plane ms-2"></i></button>
                            </form>
                            
                            <div class="mt-4 pt-4 border-top border-secondary text-center">
                                <p class="small mb-0 opacity-50">Need immediate help?</p>
                                <p class="fw-bold">+44 (0) 1234 567890</p>
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