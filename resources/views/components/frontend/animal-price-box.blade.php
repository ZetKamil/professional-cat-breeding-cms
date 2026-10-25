@props(['animal'])

@if($animal->shouldShowPrice())
    <div class="animal-profile__price-box">
        <div class="animal-profile__price-banner-content">
            <span class="animal-profile__price-label">CENA KOCIAKA (OPCJA „NA KOLANKA”)</span>
            <div class="animal-profile__price-row">
                <span class="animal-profile__price-val">{{ $animal->formattedPrice() }}</span>
                <span class="animal-profile__price-tagline">— opcja „na kolanka” z rodowodem</span>
            </div>
            <p class="animal-profile__price-subtext">
                W cenie: wielopokoleniowy rodowód SHiOZ, książeczka zdrowia (komplet szczepień i odrobaczeń) oraz umowa kupna-sprzedaży.
            </p>
        </div>
    </div>
@endif
