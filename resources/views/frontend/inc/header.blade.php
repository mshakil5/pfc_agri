    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-phone me-2"></i> {{ $company->phone1 }}
                <i class="fas fa-envelope ms-4 me-2"></i> {{ $company->email1 }}
            </div>
            <div class="d-flex align-items-center">
                <a href="{{ $company->facebook }}" class="social-link"><i class="fab fa-facebook-f me-3"></i></a>
                <a href="{{ $company->instagram }}" class="social-link"><i class="fab fa-instagram me-3"></i></a>
                <a href="{{ $company->linkedin }}" class="social-link"><i class="fab fa-linkedin-in me-3"></i></a>
                <a href="{{ $company->website }}" class="social-link"><i class="fas fa-globe me-2"></i></a>
                
                
                
                


                @php
                    $locale = app()->getLocale();
                @endphp

                <div class="dropdown d-inline">
                    <span class="dropdown-toggle d-flex align-items-center gap-2 cursor-pointer"
                        data-bs-toggle="dropdown">
                        {{-- <img src="{{ asset('resources/flags/' . ($locale ?? 'gb') . '.svg') }}"
                            width="18" height="14" alt="flag"> --}}
                        <span class="text-capitalize">
                            {{ match($locale) {
                                'de' => 'German',
                                'fr' => 'French',
                                'es' => 'Spanish',
                                'it' => 'Italian',
                                default => 'English'
                            } }}
                        </span>
                    </span>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                            href="{{ url('lang/en') }}">
                                <img src="{{ asset('resources/flags/gb.svg') }}" width="18" height="14">
                                English
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                            href="{{ url('lang/it') }}">
                                <img src="{{ asset('resources/flags/it.svg') }}" width="18" height="14">
                                Italian
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                            href="{{ url('lang/es') }}">
                                <img src="{{ asset('resources/flags/es.svg') }}" width="18" height="14">
                                Spanish
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                            href="{{ url('lang/de') }}">
                                <img src="{{ asset('resources/flags/de.svg') }}" width="18" height="14">
                                German
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                            href="{{ url('lang/fr') }}">
                                <img src="{{ asset('resources/flags/fr.svg') }}" width="18" height="14">
                                French
                            </a>
                        </li>
                    </ul>
                </div>


            </div>
        </div>
    </div>

    @php
        $categories = \App\Models\Category::with('products')->where('status', 1)->get();
    @endphp

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
                            @foreach ($categories as $category)
                            <li><a class="dropdown-item" href="{{ route('category.show', $category->slug) }}">{{$category->name}}</a></li>
                            @endforeach
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="{{route('rAndD')}}">R&D</a></li>
                    <li class="nav-item ms-lg-3"><a class="btn-inquire" href="{{route('inquire')}}">Inquire Now</a></li>
                </ul>
            </div>
        </div>
    </nav>