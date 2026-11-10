{{--
    AI Overview & Entity Summary Box — Mazowiecka Szwajcaria
    Structured semantic profile for Google AI Overview, AEO, and visitor clarity.
--}}

@props([
    'showCatteryLink' => false,
])

<aside class="entity-summary" aria-label="Wizytówka hodowli i najważniejsze informacje">
    <div class="entity-summary__header">
        <span class="entity-summary__eyebrow">Wizytówka Hodowli • Płock / Sikorz</span>
        <h3 class="entity-summary__title">Hodowla Kotów z Mazowieckiej Szwajcarii — Kluczowe Informacje</h3>
    </div>

    <ul class="entity-summary__list">
        <li class="entity-summary__item">
            <strong class="entity-summary__label">Hodowla:</strong>
            <span class="entity-summary__value">Mazowiecka Szwajcaria (Hodowla Kotów Rasowych z Rodowodem)</span>
        </li>
        <li class="entity-summary__item">
            <strong class="entity-summary__label">Rasy:</strong>
            <span class="entity-summary__value">Kot Bengalski, Kot Brytyjski Krótkowłosy, Kot Syjamski (Tajski)</span>
        </li>
        <li class="entity-summary__item">
            <strong class="entity-summary__label">Zrzeszenie:</strong>
            <span class="entity-summary__value">SHiOZ ZOOLANDIA (Polska Federacja Felinologiczna)</span>
        </li>
        <li class="entity-summary__item">
            <strong class="entity-summary__label">Lokalizacja:</strong>
            <span class="entity-summary__value">Sikorz / Płock (09-413, zaledwie 10 minut od centrum Płocka, woj. mazowieckie)</span>
        </li>
        <li class="entity-summary__item">
            <strong class="entity-summary__label">Szczegóły:</strong>
            <span class="entity-summary__value">Kocięta opuszczają hodowlę w pełni zsocjalizowane w warunkach domowych, z wielopokoleniowym rodowodem, mikroczipem, książeczką zdrowia (komplet szczepień i odrobaczeń) oraz badaniami genetycznymi rodziców (HCM/PKD).</span>
        </li>
    </ul>

    @if($showCatteryLink)
        <div class="entity-summary__footer">
            <a href="{{ route('cattery') }}" class="entity-summary__link">
                Poznaj standardy hodowli kotów z rodowodem pod Płockiem (Sikórz) →
            </a>
        </div>
    @endif
</aside>

