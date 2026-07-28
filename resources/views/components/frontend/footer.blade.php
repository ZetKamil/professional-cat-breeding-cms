<footer class="footer-area bg-dark">
    <div class="footer-widget-area section_padding_100">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="footer-widget">
                        <h6 class="widget-title">Over deze site</h6>
                        <div class="footer-logo mb-3">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('frontend/gazette/img/core-img/logo.png') }}" alt="{{ config('app.name') }}">
                            </a>
                        </div>
                        <p>Deze frontend wordt opgebouwd vanuit de originele Gazette homepagina en omgezet naar Laravel Blade.</p>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                    <div class="footer-widget">
                        <h6 class="widget-title">Navigatie</h6>
                        <ul>
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('about') }}">About</a></li>
                            <li><a href="{{ route('contact') }}">Contact</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="footer-widget">
                        <h6 class="widget-title">Frontend status</h6>
                        <p class="mb-0">
                            De homepage werkt nu op basis van de originele Gazette-structuur en assets.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-footer-area">
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-between">
                <div class="col-12 col-sm-6">
                    <p>&copy; {{ now()->year }} {{ config('app.name', 'Laravel Blog') }}</p>
                </div>

                <div class="col-12 col-sm-6">
                    <div class="footer-social-info text-right">
                        <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
