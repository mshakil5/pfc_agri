<div class="app-menu navbar-menu">
    <div class="navbar-brand-box">
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img  src="{{ asset('images/company/' . $company->company_logo) }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img  src="{{ asset('images/company/' . $company->company_logo) }}" alt="" height="25">
            </span>
        </a>
        <a href="{{ route('dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img  src="{{ asset('images/company/' . $company->company_logo) }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img  src="{{ asset('images/company/' . $company->company_logo) }}" alt="" height="25">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">

                @php
                
                    $frontPageActive = Route::is(
                        'allslider',
                        'admin.aboutUs',
                    );

                    $productActive = Route::is(
                        'allcategory',
                        'allproducts',
                        'alltags',
                    );
                    $settingsRoute = Route::is(
                        'admin.companyDetails',
                        'admin.company.seo-meta',
                        'admin.privacy-policy',
                        'admin.terms-and-conditions',
                        'faq.index',
                        'admin.mail-body',
                        'sections.index',
                        'admin.about',
                        'admin.home-footer',
                        'admin.copyright'
                    );

                    $researchPageActive = Route::is(
                        'admin.research',
                        'admin.initiatives',
                    );

                @endphp

                <li class="nav-item d-none">
                    <a class="nav-link menu-link" href="#sidebarMultilevel" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarMultilevel">
                        <i class="ri-share-line"></i> <span data-key="t-multi-level">Multi Level</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarMultilevel">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-key="t-level-1.1"> Level 1.1 </a>
                            </li>
                            <li class="nav-item">
                                <a href="#sidebarAccount" class="nav-link" data-bs-toggle="collapse"
                                    role="button" aria-expanded="false" aria-controls="sidebarAccount"
                                    data-key="t-level-1.2"> Level
                                    1.2
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarAccount">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link" data-key="t-level-2.1">
                                                Level 2.1 </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#sidebarCrm" class="nav-link"
                                                data-bs-toggle="collapse" role="button"
                                                aria-expanded="false" aria-controls="sidebarCrm"
                                                data-key="t-level-2.2"> Level 2.2
                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebarCrm">
                                                <ul class="nav nav-sm flex-column">
                                                    <li class="nav-item">
                                                        <a href="#" class="nav-link"
                                                            data-key="t-level-3.1"> Level 3.1
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#" class="nav-link"
                                                            data-key="t-level-3.2"> Level 3.2
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                

                <li class="nav-item">
                    <a class="nav-link menu-link {{ $productActive ? 'active' : '' }}" 
                      href="#sidebarAllProducts" data-bs-toggle="collapse" role="button"
                      aria-expanded="{{ $productActive ? 'true' : 'false' }}" 
                      aria-controls="sidebarAllProducts">
                        <i class="ri-shopping-bag-3-line"></i> <span>Product Management</span>
                    </a>

                    <div class="collapse menu-dropdown {{ $productActive ? 'show' : '' }}" id="sidebarAllProducts">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('allcategory') }}" 
                                  class="nav-link {{ Route::is('allcategory') ? 'active' : '' }}">Category</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('allproducts') }}" 
                                  class="nav-link {{ Route::is('allproducts') ? 'active' : '' }}">Product</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('alltags') }}" 
                                  class="nav-link {{ Route::is('alltags') ? 'active' : '' }}">Tag</a>
                            </li>
                        </ul>
                    </div>
                </li>


                
                <li class="nav-item">
                    <a class="nav-link menu-link {{ $frontPageActive ? 'active' : '' }}" 
                      href="#sidebarFrontpage" data-bs-toggle="collapse" role="button"
                      aria-expanded="{{ $frontPageActive ? 'true' : 'false' }}" 
                      aria-controls="sidebarFrontpage">
                        <i class="ri-shopping-bag-3-line"></i> <span>Home Page</span>
                    </a>
                    <div class="collapse menu-dropdown {{ $frontPageActive ? 'show' : '' }}" id="sidebarFrontpage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('allslider') }}" 
                                  class="nav-link {{ Route::is('allslider') ? 'active' : '' }}">Sliders
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.aboutUs') }}" 
                                  class="nav-link {{ Route::is('admin.aboutUs') ? 'active' : '' }}">About Us</a>
                            </li>
                        </ul>
                    </div>
                </li>


                <li class="nav-item">
                    <a class="nav-link menu-link {{ $researchPageActive ? 'active' : '' }}" 
                      href="#sidebarResearchPage" data-bs-toggle="collapse" role="button"
                      aria-expanded="{{ $researchPageActive ? 'true' : 'false' }}" 
                      aria-controls="sidebarResearchPage">
                        <i class="ri-shopping-bag-3-line"></i> <span>R&D</span>
                    </a>
                    <div class="collapse menu-dropdown {{ $researchPageActive ? 'show' : '' }}" id="sidebarResearchPage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.research') }}" 
                                  class="nav-link {{ Route::is('admin.research') ? 'active' : '' }}">Research
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.initiatives') }}" 
                                  class="nav-link {{ Route::is('admin.initiatives') ? 'active' : '' }}">Initiatives</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.about') }}" class="nav-link {{ Route::is('admin.about') ? 'active' : '' }}">
                        <i class="ri-mail-open-line"></i>
                        <span>About Us</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('alldealer') }}" class="nav-link {{ Route::is('alldealer') ? 'active' : '' }}">
                        <i class="ri-mail-open-line"></i>
                        <span>Partners</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('blog.index') }}" class="nav-link {{ Route::is('blog.index') ? 'active' : '' }}">
                        <i class="ri-mail-open-line"></i>
                        <span>Blog</span>
                    </a>
                </li>



                <li class="nav-item">
                    <a href="{{ route('contacts.index') }}" class="nav-link {{ Route::is('contacts.index') ? 'active' : '' }}">
                        <i class="ri-mail-open-line"></i>
                        <span> Messages</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('user.index') }}" class="nav-link {{ Route::is('user.index') ? 'active' : '' }}">
                        <i class="ri-user-3-line"></i>
                        <span>Admin</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ $settingsRoute ? 'active' : '' }}" 
                      href="#sidebarSettings" data-bs-toggle="collapse" role="button" 
                      aria-expanded="{{ $settingsRoute ? 'true' : 'false' }}" 
                      aria-controls="sidebarSettings">
                        <i class="ri-settings-3-line"></i> <span>Settings</span>
                    </a>

                    <div class="collapse menu-dropdown {{ $settingsRoute ? 'show' : '' }}" id="sidebarSettings">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.companyDetails') }}" 
                                  class="nav-link {{ Route::is('admin.companyDetails') ? 'active' : '' }}">Company Details</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.company.seo-meta') }}" 
                                  class="nav-link {{ Route::is('admin.company.seo-meta') ? 'active' : '' }}">SEO</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.privacy-policy') }}" 
                                  class="nav-link {{ Route::is('admin.privacy-policy') ? 'active' : '' }}">Privacy Policy</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.terms-and-conditions') }}" 
                                  class="nav-link {{ Route::is('admin.terms-and-conditions') ? 'active' : '' }}">Terms & Conditions</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.mail-body') }}" 
                                  class="nav-link {{ Route::is('admin.mail-body') ? 'active' : '' }}">Mail Body</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.home-footer') }}" 
                                  class="nav-link {{ Route::is('admin.home-footer') ? 'active' : '' }}">Home Footer</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.copyright') }}" 
                                  class="nav-link {{ Route::is('admin.copyright') ? 'active' : '' }}">Copyright</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('sections.index') }}" 
                                  class="nav-link {{ Route::is('sections.index') ? 'active' : '' }}">Section Settings</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('faq.index') }}" 
                                  class="nav-link {{ Route::is('faq.index') ? 'active' : '' }}">FAQ</a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</div>