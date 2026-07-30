@props([
    'type' => 'card', // 'card', 'text', 'image', 'avatar', 'profile'
    'lines' => 3,
    'class' => '',
])

@if ($type === 'card')
    <div class="skeleton-card {{ $class }}" aria-hidden="true">
        <div class="skeleton-card__image skeleton-shimmer"></div>
        <div class="skeleton-card__body">
            <div class="skeleton-line skeleton-shimmer" style="width: 30%; height: 12px; margin-bottom: 0.75rem;"></div>
            <div class="skeleton-line skeleton-shimmer" style="width: 75%; height: 22px; margin-bottom: 0.5rem;"></div>
            <div class="skeleton-line skeleton-shimmer" style="width: 90%; height: 14px; margin-bottom: 1.25rem;"></div>
            <div class="skeleton-card__footer">
                <div class="skeleton-line skeleton-shimmer" style="width: 40%; height: 14px;"></div>
                <div class="skeleton-line skeleton-shimmer" style="width: 28px; height: 28px; border-radius: 50%;"></div>
            </div>
        </div>
    </div>
@elseif ($type === 'text')
    <div class="skeleton-text-group {{ $class }}" aria-hidden="true">
        @for ($i = 0; $i < $lines; $i++)
            <div 
                class="skeleton-line skeleton-shimmer" 
                style="width: {{ $i === $lines - 1 ? '60%' : ($i % 2 === 0 ? '100%' : '88%') }}; height: 14px; margin-bottom: 0.625rem;"
            ></div>
        @endfor
    </div>
@elseif ($type === 'image')
    <div class="skeleton-image skeleton-shimmer {{ $class }}" aria-hidden="true"></div>
@elseif ($type === 'avatar')
    <div class="skeleton-avatar skeleton-shimmer {{ $class }}" aria-hidden="true"></div>
@elseif ($type === 'profile')
    <div class="skeleton-profile {{ $class }}" aria-hidden="true">
        <div class="skeleton-profile__header">
            <div class="skeleton-avatar skeleton-shimmer" style="width: 64px; height: 64px;"></div>
            <div class="skeleton-profile__info" style="flex: 1;">
                <div class="skeleton-line skeleton-shimmer" style="width: 45%; height: 20px; margin-bottom: 0.5rem;"></div>
                <div class="skeleton-line skeleton-shimmer" style="width: 25%; height: 14px;"></div>
            </div>
        </div>
        <div class="skeleton-text-group" style="margin-top: 1.25rem;">
            <div class="skeleton-line skeleton-shimmer" style="width: 100%; height: 14px; margin-bottom: 0.5rem;"></div>
            <div class="skeleton-line skeleton-shimmer" style="width: 85%; height: 14px;"></div>
        </div>
    </div>
@endif
