@props(['animal'])

@if($animal->shouldShowPrice())
    <div class="animal-profile__price-box">
        <div class="animal-profile__price-banner-content">
            <span class="animal-profile__price-label">KOCIAK W OPCJI „NA KOLANKA”</span>
            <div class="animal-profile__price-row">
                <span class="animal-profile__price-val">{{ $animal->formattedPrice() }}</span>
                <span class="animal-profile__price-tagline">— pupil domowy z rodowodem</span>
            </div>
            <p class="animal-profile__price-subtext">
                W pakiecie z maluchem: wielopokoleniowy rodowód SHiOZ, książeczka zdrowia (komplet szczepień i 2 odrobaczenia) oraz umowa kupna-sprzedaży.
            </p>
        </div>
    </div>
@endif
