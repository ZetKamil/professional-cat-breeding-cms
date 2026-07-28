<header class="header-area">
    <div class="top-header">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12 col-md-6">
                    <div class="breaking-news-area">
                        <h5 class="breaking-news-title">Breaking news</h5>
                        <div id="breakingNewsTicker" class="ticker">
                            <ul>
                                <li><a href="#">Welkom op onze Laravel blogfrontend.</a></li>
                                <li><a href="#">We zetten de Gazette homepagina stap voor stap om.</a></li>
                                <li><a href="#">Nieuwe artikels verschijnen hier automatisch.</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="stock-news-area">
                        <div id="stockNewsTicker" class="ticker">
                            <ul>
                                <li>
                                    <div class="single-stock-report">
                                        <div class="stock-values">
                                            <span>posts</span>
                                            <span>live</span>
                                        </div>
                                        <div class="stock-index plus-index">
                                            <h4>+1</h4>
                                        </div>
                                    </div>
                                    <div class="single-stock-report">
                                        <div class="stock-values">
                                            <span>frontend</span>
                                            <span>gazette</span>
                                        </div>
                                        <div class="stock-index plus-index">
                                            <h4>+1</h4>
                                        </div>
                                    </div>
                                    <div class="single-stock-report">
                                        <div class="stock-values">
                                            <span>cms</span>
                                            <span>laravel</span>
                                        </div>
                                        <div class="stock-index minus-index">
                                            <h4>-1</h4>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="middle-header">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="logo-area">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('frontend/gazette/img/core-img/logo.png') }}" alt="{{ config('app.name') }}">
                        </a>
                    </div>
                </div>

                <div class="col-12 col-md-8">
                    <div class="header-advert-area">
                        <a href="#">
                            <img src="{{ asset('frontend/gazette/img/bg-img/top-advert.png') }}" alt="advertentie">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-header">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12">
                    <div class="main-menu">
                        <nav class="navbar navbar-expand-lg">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#gazetteMenu" aria-controls="gazetteMenu" aria-expanded="false" aria-label="Toggle navigation">
                                <i class="fa fa-bars"></i> Menu
                            </button>

                            <div class="collapse navbar-collapse" id="gazetteMenu">
                                <ul class="navbar-nav mr-auto">
                                    <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                                    </li>

                                    <li class="nav-item {{ request()->routeIs('about') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('about') }}">About</a>
                                    </li>

                                    <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                                    </li>

                                    @auth
                                        @if(Route::has('backend.dashboard'))
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('backend.dashboard') }}">Dashboard</a>
                                            </li>
                                        @endif
                                    @endauth
                                </ul>

                                <div class="header-search-form mr-auto">
                                    <form action="#" method="post">
                                        <input type="search" placeholder="Input your keyword then press enter..." id="search" name="search">
                                    </form>
                                </div>

                                <div id="searchbtn">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
