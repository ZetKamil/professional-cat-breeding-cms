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
            headline="Rodzice i Rodowód"
            description="Nasza linia hodowlana opiera się na wyselekcjonowanych, wielopokoleniowych rodowodach FIFe/FPL."
        />

        <div class="pedigree-grid">
            @if($mother)
                <a
                    href="{{ route('frontend.animals.show', $mother) }}"
                    class="pedigree-card"
                >
                    <div class="pedigree-card__icon">
                        <i data-lucide="heart" aria-hidden="true"></i>
                    </div>
                    <div class="pedigree-card__info">
                        <span class="pedigree-card__role">Matka (Queen)</span>
                        <span class="pedigree-card__name">{{ $mother->name }}</span>
                        <span class="pedigree-card__breed">{{ $mother->breed }} • {{ $mother->color }}</span>
                    </div>
                </a>
            @endif

            @if($father)
                <a
                    href="{{ route('frontend.animals.show', $father) }}"
                    class="pedigree-card"
                >
                    <div class="pedigree-card__icon">
                        <i data-lucide="shield" aria-hidden="true"></i>
                    </div>
                    <div class="pedigree-card__info">
                        <span class="pedigree-card__role">Ojciec (Stud)</span>
                        <span class="pedigree-card__name">{{ $father->name }}</span>
                        <span class="pedigree-card__breed">{{ $father->breed }} • {{ $father->color }}</span>
                    </div>
                </a>
            @endif
        </div>
    </x-frontend.section>
@endif
