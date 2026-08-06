{{--
    Animal Pedigree Component — Luxury Design System
    
    Usage:
    <x-frontend.animal-pedigree :mother="$animal->mother" :father="$animal->father" />
    
    Props:
    - mother: optional parent model
    - father: optional parent model
--}}

@props([
    'mother' => null,
    'father' => null,
])

@if($mother || $father)
    <x-frontend.section tile="parchment" id="rodowod" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Genealogia"
            headline="Rodowód i Linie Genetyczne"
            description="Nasza linia hodowlana opiera się na wyselekcjonowanych, wielopokoleniowych rodowodach wolnych od obciążeń genetycznych."
        />

        {{-- 1. PARENTS GENERATION --}}
        <div class="pedigree-section">
            <div class="pedigree-section__header">
                <span class="pedigree-section__badge">Pokolenie 01</span>
                <span class="text-nav text-ink-muted-48">Bezpośredni rodzice miotu</span>
            </div>

            <div class="pedigree-grid">
                @if($mother)
                    <a
                        href="{{ route('frontend.animals.show', $mother) }}"
                        class="pedigree-card"
                        aria-label="Profil matki: {{ $mother->name }}"
                    >
                        <div class="pedigree-card__icon" aria-hidden="true">
                            @if($mother->media)
                                <img src="{{ $mother->media->url() }}" alt="{{ $mother->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 9999px;">
                            @else
                                <i data-lucide="heart" aria-hidden="true"></i>
                            @endif
                        </div>
                        <div class="pedigree-card__info">
                            <span class="pedigree-card__role">Matka (Queen)</span>
                            <span class="pedigree-card__name">{{ $mother->name }}</span>
                            <span class="pedigree-card__breed">{{ $mother->breed }} • {{ $mother->color }}</span>
                        </div>
                    </a>
                @else
                    <div class="pedigree-card pedigree-card--placeholder" aria-hidden="true">
                        <div class="pedigree-card__icon" aria-hidden="true">
                            <i data-lucide="award" aria-hidden="true"></i>
                        </div>
                        <div class="pedigree-card__info">
                            <span class="pedigree-card__role">Matka (Queen)</span>
                            <span class="pedigree-card__name">Linia rodowodowa</span>
                            <span class="pedigree-card__breed">Pełna dokumentacja w księgach</span>
                        </div>
                    </div>
                @endif

                @if($father)
                    <a
                        href="{{ route('frontend.animals.show', $father) }}"
                        class="pedigree-card"
                        aria-label="Profil ojca: {{ $father->name }}"
                    >
                        <div class="pedigree-card__icon" aria-hidden="true">
                            @if($father->media)
                                <img src="{{ $father->media->url() }}" alt="{{ $father->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 9999px;">
                            @else
                                <i data-lucide="shield" aria-hidden="true"></i>
                            @endif
                        </div>
                        <div class="pedigree-card__info">
                            <span class="pedigree-card__role">Ojciec (Stud)</span>
                            <span class="pedigree-card__name">{{ $father->name }}</span>
                            <span class="pedigree-card__breed">{{ $father->breed }} • {{ $father->color }}</span>
                        </div>
                    </a>
                @else
                    <div class="pedigree-card pedigree-card--placeholder" aria-hidden="true">
                        <div class="pedigree-card__icon" aria-hidden="true">
                            <i data-lucide="award" aria-hidden="true"></i>
                        </div>
                        <div class="pedigree-card__info">
                            <span class="pedigree-card__role">Ojciec (Stud)</span>
                            <span class="pedigree-card__name">Linia rodowodowa</span>
                            <span class="pedigree-card__breed">Pełna dokumentacja w księgach</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- 2. GRANDPARENTS / LINEAGE GUARANTEE STRIP --}}
        <div class="pedigree-lineage-strip mt-8" role="region" aria-label="Linie genetyczne i dziadkowie">
            <div class="pedigree-lineage__header">
                <span class="pedigree-section__badge">Pokolenie 02 & Linia</span>
                <span class="text-nav text-ink-muted-48">Gwarancje pięciopokoleniowe</span>
            </div>
            <div class="pedigree-lineage__grid">
                <div class="pedigree-lineage-card">
                    <i data-lucide="git-commit-vertical" class="pedigree-lineage__icon" aria-hidden="true"></i>
                    <div>
                        <h4 class="pedigree-lineage__title">Linia Matki (Maternal Lineage)</h4>
                        <p class="pedigree-lineage__desc">
                            @if($mother && ($mother->mother || $mother->father))
                                Potomstwo udokumentowanych linii: {{ $mother->mother->name ?? 'FPL Registered Queen' }} & {{ $mother->father->name ?? 'FPL Registered Stud' }}.
                            @else
                                Dziadkowie zarejestrowani w Międzynarodowych Księgach Rodowodowych FIFe / FPL.
                            @endif
                        </p>
                    </div>
                </div>
                <div class="pedigree-lineage-card">
                    <i data-lucide="git-commit-vertical" class="pedigree-lineage__icon" aria-hidden="true"></i>
                    <div>
                        <h4 class="pedigree-lineage__title">Linia Ojca (Paternal Lineage)</h4>
                        <p class="pedigree-lineage__desc">
                            @if($father && ($father->mother || $father->father))
                                Potomstwo udokumentowanych linii: {{ $father->mother->name ?? 'FPL Registered Queen' }} & {{ $father->father->name ?? 'FPL Registered Stud' }}.
                            @else
                                Dziadkowie zarejestrowani w Międzynarodowych Księgach Rodowodowych FIFe / FPL.
                            @endif
                        </p>
                    </div>
                </div>
                <div class="pedigree-lineage-card">
                    <i data-lucide="check-circle-2" class="pedigree-lineage__icon" aria-hidden="true"></i>
                    <div>
                        <h4 class="pedigree-lineage__title">Weryfikacja Genetyczna</h4>
                        <p class="pedigree-lineage__desc">
                            Brak obciążeń genetycznych (HCM, PKD n/n) w 5 ostatnich pokoleniach hodowlanych.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </x-frontend.section>
@endif
