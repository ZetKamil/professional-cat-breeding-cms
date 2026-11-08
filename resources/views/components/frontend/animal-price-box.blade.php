@props(['animal'])

@if($animal->shouldShowPrice())
    <div class="animal-profile__price-box">
        <div class="animal-profile__price-banner-content">
            <span class="animal-profile__price-label">KOCIAK W OPCJI „NA KOLANKA”</span>
            <div class="animal-profile__price-row">
                <span class="animal-profile__price-prefix-title">Cena kociaka „na kolanka”:</span>
                <span class="animal-profile__price-val">{{ $animal->formattedPrice() }}</span>
            </div>
            <p class="animal-profile__price-subtext">
                W cenie: wielopokoleniowy rodowód SHiOZ ZOOLANDIA, książeczka zdrowia z kompletem szczepień, mikroczip oraz wyprawka na pierwsze dni.
            </p>
        </div>
    </div>
@endif
