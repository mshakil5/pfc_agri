    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-phone me-2"></i> +44 (0) 10nal 123456 
                <i class="fas fa-envelope ms-4 me-2"></i> info@pfcagri.co.uk
            </div>
            <div class="d-flex align-items-center">
                <i class="fab fa-facebook-f me-3"></i>
                <i class="fab fa-instagram me-3"></i>
                <i class="fab fa-linkedin-in me-3"></i>
                <i class="fas fa-globe me-2"></i>
                <div class="dropdown d-inline">
                    <span class="dropdown-toggle" data-bs-toggle="dropdown">English</span>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">English</a></li>
                        <li><a class="dropdown-item" href="#">Italian</a></li>
                        <li><a class="dropdown-item" href="#">Spanish</a></li>
                        <li><a class="dropdown-item" href="#">German</a></li>
                        <li><a class="dropdown-item" href="#">French</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-success" href="{{route('home')}}">PFC Agri</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{route('home')}}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('aboutUs')}}">About Us</a></li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="prodDrop" role="button" data-bs-toggle="dropdown">
                            Products
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="product.html">Slurry Management</a></li>
                            <li><a class="dropdown-item" href="product.html">Wet Management</a></li>
                            <li><a class="dropdown-item" href="product.html">Field Preparation</a></li>
                            <li><a class="dropdown-item" href="product.html">Woodland Management</a></li>
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="{{route('rAndD')}}">R&D</a></li>
                    <li class="nav-item ms-lg-3"><a class="btn-inquire" href="{{route('inquire')}}">Inquire Now</a></li>
                </ul>
            </div>
        </div>
    </nav>