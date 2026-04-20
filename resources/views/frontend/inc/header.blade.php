<div class="top-bar">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-phone me-2"></i> {{ $company->phone1 ?? '07523270710' }}
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
                <span class="dropdown-toggle d-flex align-items-center gap-2 cursor-pointer" data-bs-toggle="dropdown">
                    <span class="text-capitalize">
                        {{ match($locale) {
                            'de' => __('header.german'),
                            'fr' => __('header.french'),
                            'es' => __('header.spanish'),
                            'it' => __('header.italian'),
                            default => __('header.english')
                        } }}
                    </span>
                </span>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 {{ $locale === 'en' ? 'active' : '' }}"
                           href="{{ route('lang.switch', 'en') }}">
                            <img src="{{ asset('resources/flags/gb.svg') }}" width="18" height="14">
                            {{ __('header.english') }}
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 {{ $locale === 'it' ? 'active' : '' }}"
                           href="{{ route('lang.switch', 'it') }}">
                            <img src="{{ asset('resources/flags/it.svg') }}" width="18" height="14">
                            {{ __('header.italian') }}
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 {{ $locale === 'es' ? 'active' : '' }}"
                           href="{{ route('lang.switch', 'es') }}">
                            <img src="{{ asset('resources/flags/es.svg') }}" width="18" height="14">
                            {{ __('header.spanish') }}
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 {{ $locale === 'de' ? 'active' : '' }}"
                           href="{{ route('lang.switch', 'de') }}">
                            <img src="{{ asset('resources/flags/de.svg') }}" width="18" height="14">
                            {{ __('header.german') }}
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 {{ $locale === 'fr' ? 'active' : '' }}"
                           href="{{ route('lang.switch', 'fr') }}">
                            <img src="{{ asset('resources/flags/fr.svg') }}" width="18" height="14">
                            {{ __('header.french') }}
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
        <a class="navbar-brand fw-bold text-success" href="{{ route('home') }}">
            <img src="{{ asset('images/company/' . $company->company_logo) }}" alt="Company Logo" height="40">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">{{ __('header.home') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('aboutUs') }}">{{ __('header.about_us') }}</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="prodDrop" role="button" data-bs-toggle="dropdown">
                        {{ __('header.products') }}
                    </a>

                    <ul class="dropdown-menu">
                        @foreach ($categories as $category)
                            <li>
                                <a class="dropdown-item" href="{{ route('category.show', $category->slug) }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('rAndD') }}">{{ __('header.r_and_d') }}</a>
                </li>

                <li class="nav-item ms-lg-3">
                    <a class="btn-inquire" href="{{ route('inquire') }}">{{ __('header.inquire_now') }}</a>
                </li>
            </ul>
        </div>
    </div>
</nav>