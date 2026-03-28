<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ ($blocks['hero']['bride_name'] ?? 'M') }} & {{ ($blocks['hero']['groom_name'] ?? 'A') }} — Приглашение на свадьбу</title>
    <link rel="icon" type="image/webp" href="{{ asset('images/sticker.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/sticker.webp') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=Great+Vibes&family=Raleway:wght@300;400;500;600&family=Cormorant+SC:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        window.weddingBlocks = @json($blocks);
        window.csrfToken = '{{ csrf_token() }}';
        window.yandexMapsApiKey = @json(config('services.yandex_maps.api_key'));
    </script>
</head>
<body>

<!-- ═══════════════════════════════════════
     ENVELOPE SCREEN
═══════════════════════════════════════ -->
<div id="envelope-screen">
    <!-- Decorative corner ornaments -->
    <div class="env-corner env-corner-tl"></div>
    <div class="env-corner env-corner-tr"></div>
    <div class="env-corner env-corner-bl"></div>
    <div class="env-corner env-corner-br"></div>

    <!-- Small scattered petals on background -->
    <svg class="env-bg-petal env-bg-petal-1" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
        <path d="M10 2 C13 5 13 9 10 10 C7 9 7 5 10 2Z" fill="rgba(124,28,45,0.12)"/>
        <path d="M18 10 C15 13 11 13 10 10 C11 7 15 7 18 10Z" fill="rgba(124,28,45,0.10)"/>
        <path d="M10 18 C7 15 7 11 10 10 C13 11 13 15 10 18Z" fill="rgba(124,28,45,0.12)"/>
        <path d="M2 10 C5 7 9 7 10 10 C9 13 5 13 2 10Z" fill="rgba(124,28,45,0.10)"/>
    </svg>
    <svg class="env-bg-petal env-bg-petal-2" width="16" height="16" viewBox="0 0 20 20" fill="none" aria-hidden="true">
        <path d="M10 2 C13 5 13 9 10 10 C7 9 7 5 10 2Z" fill="rgba(124,28,45,0.10)"/>
        <path d="M18 10 C15 13 11 13 10 10 C11 7 15 7 18 10Z" fill="rgba(124,28,45,0.08)"/>
        <path d="M10 18 C7 15 7 11 10 10 C13 11 13 15 10 18Z" fill="rgba(124,28,45,0.10)"/>
        <path d="M2 10 C5 7 9 7 10 10 C9 13 5 13 2 10Z" fill="rgba(124,28,45,0.08)"/>
    </svg>
    <svg class="env-bg-petal env-bg-petal-3" width="12" height="12" viewBox="0 0 20 20" fill="none" aria-hidden="true">
        <path d="M10 2 C13 5 13 9 10 10 C7 9 7 5 10 2Z" fill="rgba(201,169,110,0.2)"/>
        <path d="M18 10 C15 13 11 13 10 10 C11 7 15 7 18 10Z" fill="rgba(201,169,110,0.18)"/>
        <path d="M10 18 C7 15 7 11 10 10 C13 11 13 15 10 18Z" fill="rgba(201,169,110,0.2)"/>
        <path d="M2 10 C5 7 9 7 10 10 C9 13 5 13 2 10Z" fill="rgba(201,169,110,0.18)"/>
    </svg>

    <div class="envelope-wrapper" id="envelope-wrapper">
        <div id="envelope" class="close env-classic">
            <div class="front flap"></div>
            <div class="front pocket"></div>
            <div class="letter">
                <p class="script env-letter-title">{{ $blocks['hero']['tagline'] ?? 'We are getting married!' }}</p>
                <div class="words line1"></div>
                <div class="words line2"></div>
                <div class="words line3"></div>
                <div class="words line4"></div>
                <p class="jost env-letter-date">{{ $blocks['hero']['date'] ?? '25/06/2026' }}</p>
            </div>
            <div class="env-wax-seal"
                 id="env-wax-seal"
                 role="button"
                 tabindex="0"
                 aria-label="Открыть приглашение">
                <div class="env-seal-anim" id="env-seal-anim">
                    <div class="env-seal-icon" aria-hidden="true">
                        <img
                            class="env-seal-stamp-icon"
                            src="{{ asset('images/stamp.png') }}"
                            width="132"
                            height="132"
                            alt="Штамп на конверте">
                    </div>
                </div>
                <p class="jost env-seal-hint">Нажмите, чтобы открыть конверт</p>
            </div>
            <div class="hearts" aria-hidden="true">
                <div class="heart a1"></div>
                <div class="heart a2"></div>
                <div class="heart a3"></div>
            </div>
        </div>
    </div>
</div>


<!-- ═══════════════════════════════════════
     MAIN INVITATION
═══════════════════════════════════════ -->
<div id="invitation" class="invitation-wrap">

    <!-- Falling rose petals (dynamically spawned by JS) -->
    <div class="falling-petals" id="falling-petals" aria-hidden="true"></div>

    <!-- Ambient gold dust particles -->
    <div class="gold-dust-container" id="gold-dust-container" aria-hidden="true"></div>

    <!-- Sparkle cursor container -->
    <div class="sparkle-container" id="sparkle-container" aria-hidden="true"></div>

    <!-- Scattered background decoration elements -->
    <div class="bg-scatter" aria-hidden="true">
        <div class="scatter-item scatter-1">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 1 L9 6 L14 8 L9 10 L8 15 L7 10 L2 8 L7 6 Z" fill="rgba(201,169,110,0.15)"/></svg>
        </div>
        <div class="scatter-item scatter-2">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><circle cx="6" cy="6" r="1.8" fill="rgba(124,28,45,0.1)"/></svg>
        </div>
        <div class="scatter-item scatter-3">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 1 L8 5.5 L12.5 7 L8 8.5 L7 13 L6 8.5 L1.5 7 L6 5.5 Z" fill="rgba(124,28,45,0.08)"/></svg>
        </div>
        <div class="scatter-item scatter-4">
            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="1.5" fill="rgba(201,169,110,0.2)"/></svg>
        </div>
        <div class="scatter-item scatter-5">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1 L7 4.5 L10.5 6 L7 7.5 L6 11 L5 7.5 L1.5 6 L5 4.5 Z" fill="rgba(201,169,110,0.12)"/></svg>
        </div>
        <div class="scatter-item scatter-6">
            <svg width="8" height="8" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="1.2" fill="rgba(124,28,45,0.12)"/></svg>
        </div>
        <div class="scatter-item scatter-7">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M9 2 L10 7 L15 9 L10 11 L9 16 L8 11 L3 9 L8 7 Z" fill="rgba(201,169,110,0.1)"/></svg>
        </div>
        <div class="scatter-item scatter-8">
            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="1.3" fill="rgba(124,28,45,0.08)"/></svg>
        </div>
        <div class="scatter-item scatter-9">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 2 L8 5.5 L11.5 7 L8 8.5 L7 12 L6 8.5 L2.5 7 L6 5.5 Z" fill="rgba(124,28,45,0.06)"/></svg>
        </div>
        <div class="scatter-item scatter-10">
            <svg width="8" height="8" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="1" fill="rgba(201,169,110,0.18)"/></svg>
        </div>
        <!-- Extra decorative elements: mini leaves & hearts -->
        <div class="scatter-item scatter-11">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M10 2 C13 5 14 9 10 14 C6 9 7 5 10 2Z" fill="rgba(201,169,110,0.1)" stroke="rgba(201,169,110,0.15)" stroke-width="0.5"/></svg>
        </div>
        <div class="scatter-item scatter-12">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 12 C7 12 2 8 2 5.5 C2 4 3.2 3 4.3 3 C5.2 3 6.2 3.5 7 4.5 C7.8 3.5 8.8 3 9.7 3 C10.8 3 12 4 12 5.5 C12 8 7 12 7 12Z" fill="rgba(124,28,45,0.06)" stroke="rgba(124,28,45,0.1)" stroke-width="0.5"/></svg>
        </div>
        <div class="scatter-item scatter-13">
            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M5 1 C7 3 7 6 5 8 C3 6 3 3 5 1Z" fill="rgba(201,169,110,0.12)"/></svg>
        </div>
        <div class="scatter-item scatter-14">
            <svg width="6" height="6" viewBox="0 0 6 6" fill="none"><circle cx="3" cy="3" r="2" fill="rgba(201,169,110,0.22)"/></svg>
        </div>
        <div class="scatter-item scatter-15">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 0.5 L7 4.5 L11 6 L7 7.5 L6 11.5 L5 7.5 L1 6 L5 4.5 Z" fill="rgba(201,169,110,0.08)"/></svg>
        </div>
    </div>


    @php
        $heroVisible = ($blocks['hero']['is_visible'] ?? true);
        $betweenTextVisible = ($blocks['between_text']['is_visible'] ?? false);
        $locationVisible = ($blocks['location']['is_visible'] ?? true);
    @endphp

    <!-- ── HERO SECTION ── -->
    @if($heroVisible)
    <section class="section-hero">
        <!-- Corner vines: top-left + bottom-right only -->
        <div class="hero-corner-ornament hero-corner-tl" aria-hidden="true">
            @include('partials.cherub-left')
        </div>
        <div class="hero-corner-ornament hero-corner-br" aria-hidden="true">
            @include('partials.cherub-right')
        </div>

        <div class="hero-content">
            <div class="hero-tag reveal">
                <span class="hero-tag-label">Свадебное приглашение</span>
            </div>

            <h1 class="script hero-tagline reveal" data-delay="100">
                {{ $blocks['hero']['tagline'] ?? 'We are getting married!' }}
            </h1>

            @if(!empty($blocks['hero']['image_path']))
            <div class="hero-photo reveal" data-delay="200">
                <img src="/{{ $blocks['hero']['image_path'] }}" alt="Пара">
                <div class="photo-overlay"></div>
            </div>
            @else
            <div class="hero-photo hero-photo-placeholder reveal" data-delay="200">
                <div class="photo-inner">
                    @include('partials.couple-silhouette')
                </div>
            </div>
            @endif

            <div class="hero-divider reveal" data-delay="300">
                <span class="divider-line"></span>
                <span class="divider-icon heart-pulse" aria-hidden="true">
                    <svg width="28" height="18" viewBox="0 0 28 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 16 C14 16 4 10 4 5.5 C4 3 6 1 8.5 1 C10.5 1 12.5 2.5 14 4.5 C15.5 2.5 17.5 1 19.5 1 C22 1 24 3 24 5.5 C24 10 14 16 14 16Z" fill="#C9A96E" fill-opacity="0.2" stroke="#C9A96E" stroke-width="0.9" stroke-linejoin="round" stroke-linecap="round"/>
                    </svg>
                </span>
                <span class="divider-line"></span>
            </div>

            <p class="jost hero-save reveal" data-delay="350">
                {{ $blocks['hero']['save_date_text'] ?? 'SAVE THE DATE' }}
            </p>
            <p class="script hero-date reveal" data-delay="400">
                {{ $blocks['hero']['date'] ?? '25/06/2026' }}
            </p>

            <p class="cormorant hero-intro reveal" data-delay="500">
                {{ $blocks['hero']['intro_text'] ?? 'Мы верим, что счастье становится полным, когда им делишься с самыми близкими людьми.' }}
            </p>

            <div class="hero-bottom-ornament reveal" data-delay="600" aria-hidden="true">
                <svg width="120" height="20" viewBox="0 0 120 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <line x1="0" y1="10" x2="46" y2="10" stroke="rgba(124,28,45,0.15)" stroke-width="0.7" stroke-linecap="round"/>
                    <line x1="74" y1="10" x2="120" y2="10" stroke="rgba(124,28,45,0.15)" stroke-width="0.7" stroke-linecap="round"/>
                    <path d="M60 4 C60 4 55 1.5 55-0.5 C55-2 56.3-3 57.5-3 C58.5-3 59.5-2 60-0.5 C60.5-2 61.5-3 62.5-3 C63.7-3 65-2 65-0.5 C65 1.5 60 4 60 4Z" fill="rgba(201,169,110,0.25)" stroke="rgba(201,169,110,0.35)" stroke-width="0.5" transform="translate(0 7)"/>
                </svg>
            </div>
        </div>
    </section>
    @endif

    <!-- ── BETWEEN TEXT SECTION ── -->
    @if($betweenTextVisible)
    <section class="section-between-text">
        <div class="between-text-shell reveal">
            <div class="between-text-corners between-text-corner-tl" aria-hidden="true"></div>
            <div class="between-text-corners between-text-corner-tr" aria-hidden="true"></div>
            <div class="between-text-corners between-text-corner-bl" aria-hidden="true"></div>
            <div class="between-text-corners between-text-corner-br" aria-hidden="true"></div>

            <div class="between-text-heart" aria-hidden="true">
                <svg width="26" height="16" viewBox="0 0 26 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13 14 C13 14 4 9 4 5.2 C4 3.2 5.6 1.7 7.6 1.7 C9.1 1.7 10.7 2.6 13 5 C15.3 2.6 16.9 1.7 18.4 1.7 C20.4 1.7 22 3.2 22 5.2 C22 9 13 14 13 14Z" fill="rgba(201,169,110,0.28)" stroke="rgba(124,28,45,0.25)" stroke-width="0.8"/>
                </svg>
            </div>

            <div class="between-text-inner">
            {!! nl2br(e($blocks['between_text']['text'] ?? '')) !!}
            </div>

            <div class="between-text-btn-wrap">
                <button type="button" class="btn-guest-anketa jost" id="btn-open-rsvp-modal">Анкета гостя</button>
            </div>
        </div>
    </section>
    @endif

    @if($locationVisible && ($betweenTextVisible || !$heroVisible))
    <div class="wedding-mini-icon reveal" aria-hidden="true">
        <svg width="30" height="24" viewBox="0 0 30 24" fill="none">
            {{-- Rose bud icon --}}
            <path d="M15 4 C18 2 22 4 22 8 C22 13 15 20 15 20 C15 20 8 13 8 8 C8 4 12 2 15 4Z" stroke="rgba(124,28,45,0.2)" stroke-width="0.8" fill="rgba(124,28,45,0.04)"/>
            <path d="M15 8 C13 6 11 7 12 9 C13 11 15 12 15 12" stroke="rgba(201,169,110,0.25)" stroke-width="0.5" fill="none"/>
            <path d="M15 8 C17 6 19 7 18 9 C17 11 15 12 15 12" stroke="rgba(201,169,110,0.25)" stroke-width="0.5" fill="none"/>
            <path d="M15 12 L15 22" stroke="rgba(201,169,110,0.2)" stroke-width="0.5" stroke-linecap="round"/>
        </svg>
    </div>
    <div class="floral-divider-wrap reveal">
        @include('partials.floral-divider')
    </div>
    @endif


    <!-- ── LOCATION SECTION ── -->
    @if($locationVisible)
    <section class="section-location">
        <!-- Corner gold accents -->
        <div class="location-corner location-corner-tl" aria-hidden="true">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none"><path d="M2 38 L2 2 L38 2" stroke="rgba(201,169,110,0.2)" stroke-width="0.8" stroke-linecap="round" fill="none"/><circle cx="2" cy="2" r="2" fill="rgba(201,169,110,0.25)"/></svg>
        </div>
        <div class="location-corner location-corner-tr" aria-hidden="true">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none"><path d="M38 38 L38 2 L2 2" stroke="rgba(201,169,110,0.2)" stroke-width="0.8" stroke-linecap="round" fill="none"/><circle cx="38" cy="2" r="2" fill="rgba(201,169,110,0.25)"/></svg>
        </div>
        <div class="location-corner location-corner-bl" aria-hidden="true">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none"><path d="M2 2 L2 38 L38 38" stroke="rgba(201,169,110,0.2)" stroke-width="0.8" stroke-linecap="round" fill="none"/><circle cx="2" cy="38" r="2" fill="rgba(201,169,110,0.25)"/></svg>
        </div>
        <div class="location-corner location-corner-br" aria-hidden="true">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none"><path d="M38 2 L38 38 L2 38" stroke="rgba(201,169,110,0.2)" stroke-width="0.8" stroke-linecap="round" fill="none"/><circle cx="38" cy="38" r="2" fill="rgba(201,169,110,0.25)"/></svg>
        </div>

        <div class="location-top-ornament" aria-hidden="true">
            <svg width="80" height="36" viewBox="0 0 80 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 34 C8 16 20 4 40 4 C60 4 72 16 72 34" stroke="rgba(201,169,110,0.35)" stroke-width="0.9" stroke-linecap="round" fill="none"/>
                <path d="M16 34 C16 20 26 10 40 10 C54 10 64 20 64 34" stroke="rgba(249,236,213,0.25)" stroke-width="0.7" stroke-linecap="round" fill="none"/>
                <circle cx="40" cy="4" r="2.5" fill="rgba(201,169,110,0.45)" stroke="rgba(201,169,110,0.3)" stroke-width="0.7"/>
                <path d="M36 22 C36 20 38 18 40 18 C42 18 44 20 44 22" stroke="rgba(249,236,213,0.4)" stroke-width="0.7" fill="none" stroke-linecap="round"/>
                <circle cx="40" cy="24" r="1" fill="rgba(201,169,110,0.5)"/>
            </svg>
        </div>

        <h2 class="script section-title-script reveal">{{ $blocks['location']['title'] ?? 'Локация' }}</h2>

        <div class="location-cards">
            @foreach(($blocks['location']['venues'] ?? []) as $venue)
            <div class="location-card reveal" data-delay="{{ $loop->index * 150 }}">
                <div class="location-card-time jost">{{ $venue['time'] }}</div>
                <div class="location-card-divider"></div>
                <div class="location-card-name cormorant">{{ $venue['name'] }}</div>
                <div class="location-card-address jost">{{ $venue['address'] }}</div>
                @if(!empty($venue['map_link']) && $venue['map_link'] !== '#')
                <a href="{{ $venue['map_link'] }}" target="_blank" class="location-btn jost">Локация ↗</a>
                @endif
            </div>
            @endforeach
        </div>

        @php
            $__loc = $blocks['location'] ?? [];
            $__addr = trim((string) ($__loc['map_address'] ?? ''));
            $__lat = $__loc['map_lat'] ?? null;
            $__lng = $__loc['map_lng'] ?? null;
            $__hasCoords = $__lat !== null && $__lat !== '' && $__lng !== null && $__lng !== ''
                && is_numeric($__lat) && is_numeric($__lng);
            $__showMap = $__hasCoords || $__addr !== '';
            $__firstVenue = (isset($__loc['venues'][0]) && is_array($__loc['venues'][0])) ? $__loc['venues'][0] : [];
            $__mapHint = $__addr !== ''
                ? $__addr
                : (trim((string) (($__firstVenue['name'] ?? '') ?: ($__firstVenue['address'] ?? ''))) ?: 'Место встречи');
        @endphp

        @if(!empty($blocks['location']['location_images']) && is_array($blocks['location']['location_images']))
        <div class="location-slider-wrap reveal">
            <div class="swiper location-swiper">
                <div class="swiper-wrapper">
                    @foreach($blocks['location']['location_images'] as $imgPath)
                    <div class="swiper-slide">
                        <figure class="location-slide-figure">
                            <img src="/{{ $imgPath }}" alt="Локация" loading="lazy" decoding="async" width="640" height="480">
                        </figure>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="swiper-button-prev location-swiper-button-prev" aria-label="Предыдущее фото"></button>
                <button type="button" class="swiper-button-next location-swiper-button-next" aria-label="Следующее фото"></button>
                <div class="swiper-pagination location-swiper-pagination" aria-hidden="true"></div>
            </div>
        </div>
        @endif

        @if($__showMap)
        <div class="location-map-shell reveal">
            <p class="location-map-heading jost">Как добраться</p>
            <div class="location-map-frame">
                <div id="wedding-map" class="location-map"
                    @if($__hasCoords) data-lat="{{ $__lat }}" data-lng="{{ $__lng }}" @endif
                    @if($__addr !== '') data-address="{{ e($__addr) }}" @endif
                    data-hint="{{ e($__mapHint) }}"
                    aria-label="Карта"></div>
            </div>
        </div>
        @endif
    </section>
    @endif

    <!-- Divider before Timing -->
    @if(($blocks['location']['is_visible'] ?? true) && ($blocks['timing']['is_visible'] ?? true))
    <div class="wedding-mini-icon reveal" aria-hidden="true">
        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
            <path d="M16 6 C16 6 9 2 9 7 C9 10 12 12 16 16 C20 12 23 10 23 7 C23 2 16 6 16 6Z" stroke="rgba(201,169,110,0.4)" stroke-width="0.8" fill="rgba(201,169,110,0.08)"/>
            <path d="M16 16 C16 16 9 12 9 17 C9 20 12 22 16 26 C20 22 23 20 23 17 C23 12 16 16 16 16Z" stroke="rgba(124,28,45,0.2)" stroke-width="0.8" fill="rgba(124,28,45,0.04)"/>
        </svg>
    </div>
    <div class="floral-divider-wrap reveal">
        @include('partials.floral-divider')
    </div>
    @endif


    <!-- ── TIMING SECTION ── -->
    @if(($blocks['timing']['is_visible'] ?? true))
    <section class="section-timing">
        <!-- Ornamental corner frames -->
        <div class="section-ornament-frame section-ornament-tl" aria-hidden="true">
            <svg width="56" height="56" viewBox="0 0 56 56" fill="none">
                <path d="M4 52 L4 4 L52 4" stroke="rgba(201,169,110,0.18)" stroke-width="0.7" stroke-linecap="round" fill="none"/>
                <path d="M4 4 C4 4 12 8 16 16" stroke="rgba(201,169,110,0.12)" stroke-width="0.5" fill="none"/>
                <circle cx="4" cy="4" r="2" fill="rgba(201,169,110,0.25)"/>
                <path d="M12 4 L12 8" stroke="rgba(201,169,110,0.12)" stroke-width="0.4"/>
                <path d="M4 12 L8 12" stroke="rgba(201,169,110,0.12)" stroke-width="0.4"/>
            </svg>
        </div>
        <div class="section-ornament-frame section-ornament-br" aria-hidden="true">
            <svg width="56" height="56" viewBox="0 0 56 56" fill="none">
                <path d="M52 4 L52 52 L4 52" stroke="rgba(201,169,110,0.18)" stroke-width="0.7" stroke-linecap="round" fill="none"/>
                <path d="M52 52 C52 52 44 48 40 40" stroke="rgba(201,169,110,0.12)" stroke-width="0.5" fill="none"/>
                <circle cx="52" cy="52" r="2" fill="rgba(201,169,110,0.25)"/>
                <path d="M44 52 L44 48" stroke="rgba(201,169,110,0.12)" stroke-width="0.4"/>
                <path d="M52 44 L48 44" stroke="rgba(201,169,110,0.12)" stroke-width="0.4"/>
            </svg>
        </div>

        <div class="section-title-ornament reveal" aria-hidden="true">
            <svg width="60" height="16" viewBox="0 0 60 16" fill="none"><line x1="0" y1="8" x2="22" y2="8" stroke="rgba(124,28,45,0.18)" stroke-width="0.6" stroke-linecap="round"/><line x1="38" y1="8" x2="60" y2="8" stroke="rgba(124,28,45,0.18)" stroke-width="0.6" stroke-linecap="round"/><path d="M30 3 L31 7 L35 8 L31 9 L30 13 L29 9 L25 8 L29 7 Z" fill="rgba(201,169,110,0.3)"/></svg>
        </div>
        <h2 class="script section-title-script reveal">{{ $blocks['timing']['title'] ?? 'Тайминг' }}</h2>

        <div class="timeline">
            @foreach(($blocks['timing']['events'] ?? []) as $event)
                @if(!empty($event['time']))
                <div class="timeline-item reveal" data-delay="{{ $loop->index * 80 }}">
                    <div class="timeline-time jost">{{ $event['time'] }}</div>
                    <div class="timeline-dot-wrap">
                        <div class="timeline-dot"></div>
                    </div>
                    <div class="timeline-content">
                        @if(!empty($event['title']))
                        <div class="timeline-title cormorant">{{ $event['title'] }}</div>
                        @endif
                        @if(!empty($event['description']))
                        <div class="timeline-desc jost">{{ $event['description'] }}</div>
                        @endif
                    </div>
                </div>
                @else
                <div class="timeline-note reveal" data-delay="{{ $loop->index * 80 }}">
                    <p class="cormorant" style="font-style:italic; color:var(--text-light); font-size:0.9rem; line-height:1.6;">
                        {{ $event['description'] }}
                    </p>
                </div>
                @endif
            @endforeach
        </div>
    </section>
    @endif

    @if(($blocks['timing']['is_visible'] ?? true) && ($blocks['dress_code']['is_visible'] ?? true))
    <div class="wedding-mini-icon reveal" aria-hidden="true">
        <svg width="36" height="28" viewBox="0 0 36 28" fill="none">
            {{-- Champagne glasses icon --}}
            <path d="M12 4 L12 14 C12 17 10 19 8 20 L8 26" stroke="rgba(201,169,110,0.35)" stroke-width="0.8" stroke-linecap="round" fill="none"/>
            <path d="M24 4 L24 14 C24 17 26 19 28 20 L28 26" stroke="rgba(201,169,110,0.35)" stroke-width="0.8" stroke-linecap="round" fill="none"/>
            <ellipse cx="12" cy="4" rx="4" ry="2" stroke="rgba(201,169,110,0.3)" stroke-width="0.7" fill="rgba(201,169,110,0.06)"/>
            <ellipse cx="24" cy="4" rx="4" ry="2" stroke="rgba(201,169,110,0.3)" stroke-width="0.7" fill="rgba(201,169,110,0.06)"/>
            <path d="M5 26 L11 26" stroke="rgba(201,169,110,0.25)" stroke-width="0.7" stroke-linecap="round"/>
            <path d="M25 26 L31 26" stroke="rgba(201,169,110,0.25)" stroke-width="0.7" stroke-linecap="round"/>
            <path d="M14 8 C16 10 20 10 22 8" stroke="rgba(124,28,45,0.15)" stroke-width="0.6" stroke-linecap="round" fill="none"/>
            <circle cx="18" cy="2" r="1" fill="rgba(201,169,110,0.3)"/>
        </svg>
    </div>
    <div class="floral-divider-wrap reveal">
        @include('partials.floral-divider')
    </div>
    @endif


    <!-- ── DRESS CODE SECTION ── -->
    @if(($blocks['dress_code']['is_visible'] ?? true))
    <section class="section-dresscode">
        <!-- Ornamental corner frames -->
        <div class="section-ornament-frame section-ornament-tl" aria-hidden="true">
            <svg width="56" height="56" viewBox="0 0 56 56" fill="none">
                <path d="M4 52 L4 4 L52 4" stroke="rgba(201,169,110,0.18)" stroke-width="0.7" stroke-linecap="round" fill="none"/>
                <path d="M4 4 C4 4 12 8 16 16" stroke="rgba(201,169,110,0.12)" stroke-width="0.5" fill="none"/>
                <circle cx="4" cy="4" r="2" fill="rgba(201,169,110,0.25)"/>
            </svg>
        </div>
        <div class="section-ornament-frame section-ornament-br" aria-hidden="true">
            <svg width="56" height="56" viewBox="0 0 56 56" fill="none">
                <path d="M52 4 L52 52 L4 52" stroke="rgba(201,169,110,0.18)" stroke-width="0.7" stroke-linecap="round" fill="none"/>
                <path d="M52 52 C52 52 44 48 40 40" stroke="rgba(201,169,110,0.12)" stroke-width="0.5" fill="none"/>
                <circle cx="52" cy="52" r="2" fill="rgba(201,169,110,0.25)"/>
            </svg>
        </div>
        <!-- Bottom left sparkle -->
        <div class="dresscode-sparkle-bl" aria-hidden="true">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 2 L13.5 9.5 L21 12 L13.5 14.5 L12 22 L10.5 14.5 L3 12 L10.5 9.5 Z" fill="rgba(201,169,110,0.12)"/></svg>
        </div>
        <!-- Top right sparkle -->
        <div class="dresscode-sparkle-tr" aria-hidden="true">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M9 1 L10.2 7 L16 9 L10.2 11 L9 17 L7.8 11 L2 9 L7.8 7 Z" fill="rgba(201,169,110,0.1)"/></svg>
        </div>
        <!-- Decorative leaf accent -->
        <div class="dresscode-leaf-accent" aria-hidden="true">
            <svg width="28" height="40" viewBox="0 0 28 40" fill="none">
                <path d="M14 2 C18 8 20 18 14 32 C8 18 10 8 14 2Z" fill="rgba(201,169,110,0.06)" stroke="rgba(201,169,110,0.12)" stroke-width="0.5"/>
                <path d="M14 8 L14 28" stroke="rgba(201,169,110,0.1)" stroke-width="0.3"/>
                <path d="M14 12 C17 10 18 14 14 16" stroke="rgba(201,169,110,0.08)" stroke-width="0.4" fill="none"/>
                <path d="M14 18 C11 16 10 20 14 22" stroke="rgba(201,169,110,0.08)" stroke-width="0.4" fill="none"/>
            </svg>
        </div>

        <div class="section-title-ornament reveal" aria-hidden="true">
            <svg width="60" height="16" viewBox="0 0 60 16" fill="none"><line x1="0" y1="8" x2="22" y2="8" stroke="rgba(124,28,45,0.18)" stroke-width="0.6" stroke-linecap="round"/><line x1="38" y1="8" x2="60" y2="8" stroke="rgba(124,28,45,0.18)" stroke-width="0.6" stroke-linecap="round"/><path d="M30 3 L31 7 L35 8 L31 9 L30 13 L29 9 L25 8 L29 7 Z" fill="rgba(201,169,110,0.3)"/></svg>
        </div>
        <h2 class="script section-title-script reveal">{{ $blocks['dress_code']['title'] ?? 'Дресс-код' }}</h2>
        <p class="cormorant dresscode-intro reveal">{{ $blocks['dress_code']['intro'] ?? '' }}</p>
        <div class="cormorant dresscode-desc reveal">
            {!! nl2br(e($blocks['dress_code']['description'] ?? '')) !!}
        </div>

        @if(!empty($blocks['dress_code']['colors']))
        <div class="color-palette reveal">
            @foreach($blocks['dress_code']['colors'] as $color)
            <div class="color-swatch-wrap" title="{{ e($color) }}">
                <div class="color-swatch-circle" style="background-color: {{ e($color) }};"></div>
            </div>
            @endforeach
        </div>
        @endif

        @if(!empty($blocks['dress_code']['image_path']))
        <div class="dresscode-photo reveal">
            <img src="/{{ $blocks['dress_code']['image_path'] }}" alt="Dress code">
        </div>
        @endif

        <p class="cormorant dresscode-outro reveal">{{ $blocks['dress_code']['outro'] ?? '' }}</p>
    </section>
    @endif

    @if(($blocks['dress_code']['is_visible'] ?? true) && ($blocks['details']['is_visible'] ?? true))
    <div class="wedding-mini-icon reveal" aria-hidden="true">
        <svg width="34" height="30" viewBox="0 0 34 30" fill="none">
            {{-- Wedding rings icon --}}
            <ellipse cx="12" cy="16" rx="9" ry="9" stroke="rgba(201,169,110,0.35)" stroke-width="0.9" fill="none"/>
            <ellipse cx="22" cy="16" rx="9" ry="9" stroke="rgba(124,28,45,0.2)" stroke-width="0.9" fill="none"/>
            <circle cx="12" cy="10" r="1.5" fill="rgba(201,169,110,0.25)"/>
            <path d="M10 10 L14 10" stroke="rgba(201,169,110,0.2)" stroke-width="0.5"/>
        </svg>
    </div>
    <div class="floral-divider-wrap reveal">
        @include('partials.floral-divider')
    </div>
    @endif


    <!-- ── DETAILS SECTION ── -->
    @if(($blocks['details']['is_visible'] ?? true))
    <section class="section-details">
        <!-- Decorative corner branches -->
        <div class="details-corner-left" aria-hidden="true">
            <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 54 C6 36 10 22 22 12" stroke="rgba(249,236,213,0.22)" stroke-width="0.8" stroke-linecap="round" fill="none"/>
                <path d="M6 54 C18 48 30 46 42 50" stroke="rgba(249,236,213,0.22)" stroke-width="0.8" stroke-linecap="round" fill="none"/>
                <ellipse cx="20" cy="14" rx="2.2" ry="3.5" fill="rgba(249,236,213,0.12)" stroke="rgba(249,236,213,0.25)" stroke-width="0.5" transform="rotate(-30 20 14)"/>
                <ellipse cx="12" cy="28" rx="2" ry="3.2" fill="rgba(249,236,213,0.1)" stroke="rgba(249,236,213,0.22)" stroke-width="0.5" transform="rotate(15 12 28)"/>
                <ellipse cx="30" cy="46" rx="2" ry="3.2" fill="rgba(249,236,213,0.1)" stroke="rgba(249,236,213,0.22)" stroke-width="0.5" transform="rotate(50 30 46)"/>
                <circle cx="24" cy="10" r="1.2" fill="rgba(201,169,110,0.35)"/>
            </svg>
        </div>
        <div class="details-corner-right" aria-hidden="true">
            <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg" style="transform:scaleX(-1)">
                <path d="M6 54 C6 36 10 22 22 12" stroke="rgba(249,236,213,0.22)" stroke-width="0.8" stroke-linecap="round" fill="none"/>
                <path d="M6 54 C18 48 30 46 42 50" stroke="rgba(249,236,213,0.22)" stroke-width="0.8" stroke-linecap="round" fill="none"/>
                <ellipse cx="20" cy="14" rx="2.2" ry="3.5" fill="rgba(249,236,213,0.12)" stroke="rgba(249,236,213,0.25)" stroke-width="0.5" transform="rotate(-30 20 14)"/>
                <ellipse cx="12" cy="28" rx="2" ry="3.2" fill="rgba(249,236,213,0.1)" stroke="rgba(249,236,213,0.22)" stroke-width="0.5" transform="rotate(15 12 28)"/>
                <ellipse cx="30" cy="46" rx="2" ry="3.2" fill="rgba(249,236,213,0.1)" stroke="rgba(249,236,213,0.22)" stroke-width="0.5" transform="rotate(50 30 46)"/>
                <circle cx="24" cy="10" r="1.2" fill="rgba(201,169,110,0.35)"/>
            </svg>
        </div>

        <div class="details-inner">
            <h2 class="script section-title-script section-details-title reveal">{{ $blocks['details']['title'] ?? 'Детали' }}</h2>
            <div class="details-content">
                <div class="details-envelope-icon reveal" data-delay="100">
                    <img src="{{ asset('images/letter.png') }}" alt="Конверт" class="details-envelope-image">
                </div>
                <p class="cormorant details-text reveal" data-delay="150">
                    {!! nl2br(e($blocks['details']['wishes_text'] ?? '')) !!}
                </p>
                <div class="details-divider reveal" data-delay="200">
                    <svg width="160" height="20" viewBox="0 0 160 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <line x1="0" y1="10" x2="58" y2="10" stroke="rgba(249,236,213,0.3)" stroke-width="0.8" stroke-linecap="round"/>
                        <line x1="102" y1="10" x2="160" y2="10" stroke="rgba(249,236,213,0.3)" stroke-width="0.8" stroke-linecap="round"/>
                        <circle cx="4" cy="10" r="1.5" fill="rgba(201,169,110,0.4)"/>
                        <circle cx="156" cy="10" r="1.5" fill="rgba(201,169,110,0.4)"/>
                        <g transform="translate(80,10)" fill="none" stroke-linecap="round">
                            <ellipse cx="5" cy="0" rx="6.5" ry="6.5" transform="rotate(20 5 0)" stroke="rgba(249,236,213,0.45)" stroke-width="1"/>
                            <ellipse cx="-5" cy="0" rx="6.5" ry="6.5" transform="rotate(-20 -5 0)" stroke="rgba(249,236,213,0.45)" stroke-width="1"/>
                            <path d="M-1.5-4.5c1.2-1.5 2.8-2 4-1" stroke="rgba(201,169,110,0.5)" stroke-width="0.7" fill="none"/>
                            <path d="M3-4.5c1.2-1.5 2.8-2 4-1" stroke="rgba(201,169,110,0.5)" stroke-width="0.7" fill="none"/>
                        </g>
                    </svg>
                </div>
                <p class="script details-gift reveal" data-delay="250">
                    {{ $blocks['details']['gift_text'] ?? '' }}
                </p>
                <div class="details-ribbon reveal" data-delay="300" aria-hidden="true">
                    <img src="{{ asset('images/lenta.png') }}" alt="" class="details-ribbon-image">
                </div>
            </div>
        </div>
    </section>
    @endif


    <!-- Floral divider -->
    @if(($blocks['details']['is_visible'] ?? true) && ($blocks['form']['is_visible'] ?? true))
    <div class="wedding-mini-icon reveal" aria-hidden="true">
        <svg width="30" height="30" viewBox="0 0 30 30" fill="none">
            {{-- Cake icon --}}
            <rect x="7" y="18" width="16" height="8" rx="1" stroke="rgba(201,169,110,0.3)" stroke-width="0.7" fill="rgba(201,169,110,0.05)"/>
            <rect x="9" y="12" width="12" height="6" rx="1" stroke="rgba(201,169,110,0.3)" stroke-width="0.7" fill="rgba(201,169,110,0.05)"/>
            <rect x="11" y="7" width="8" height="5" rx="1" stroke="rgba(201,169,110,0.3)" stroke-width="0.7" fill="rgba(201,169,110,0.05)"/>
            <path d="M15 3 L15 7" stroke="rgba(124,28,45,0.2)" stroke-width="0.6" stroke-linecap="round"/>
            <circle cx="15" cy="2.5" r="1" fill="rgba(201,169,110,0.35)"/>
            <path d="M7 21 Q11 19 15 21 Q19 23 23 21" stroke="rgba(201,169,110,0.2)" stroke-width="0.5" fill="none"/>
        </svg>
    </div>
    <div class="floral-divider-wrap reveal">
        @include('partials.floral-divider')
    </div>
    @endif

    <!-- ── GUEST FORM SECTION ── -->
    @if(($blocks['form']['is_visible'] ?? true))
    @php
        $formBlock = $blocks['form'] ?? [];
        $fo = $formBlock['form_options'] ?? [];
        $ql = $formBlock['question_labels'] ?? [];
    @endphp
    <section class="section-form" id="rsvp">
        <!-- Ornamental corner frames -->
        <div class="section-ornament-frame section-ornament-tl" aria-hidden="true">
            <svg width="56" height="56" viewBox="0 0 56 56" fill="none">
                <path d="M4 52 L4 4 L52 4" stroke="rgba(201,169,110,0.18)" stroke-width="0.7" stroke-linecap="round" fill="none"/>
                <circle cx="4" cy="4" r="2" fill="rgba(201,169,110,0.25)"/>
            </svg>
        </div>
        <div class="section-ornament-frame section-ornament-tr" aria-hidden="true">
            <svg width="56" height="56" viewBox="0 0 56 56" fill="none">
                <path d="M4 4 L52 4 L52 52" stroke="rgba(201,169,110,0.18)" stroke-width="0.7" stroke-linecap="round" fill="none"/>
                <circle cx="52" cy="4" r="2" fill="rgba(201,169,110,0.25)"/>
            </svg>
        </div>
        <div class="section-ornament-frame section-ornament-bl" aria-hidden="true">
            <svg width="56" height="56" viewBox="0 0 56 56" fill="none">
                <path d="M4 4 L4 52 L52 52" stroke="rgba(201,169,110,0.18)" stroke-width="0.7" stroke-linecap="round" fill="none"/>
                <circle cx="4" cy="52" r="2" fill="rgba(201,169,110,0.25)"/>
            </svg>
        </div>
        <div class="section-ornament-frame section-ornament-br" aria-hidden="true">
            <svg width="56" height="56" viewBox="0 0 56 56" fill="none">
                <path d="M52 4 L52 52 L4 52" stroke="rgba(201,169,110,0.18)" stroke-width="0.7" stroke-linecap="round" fill="none"/>
                <circle cx="52" cy="52" r="2" fill="rgba(201,169,110,0.25)"/>
            </svg>
        </div>
        <div class="section-title-ornament reveal" aria-hidden="true">
            <svg width="60" height="16" viewBox="0 0 60 16" fill="none"><line x1="0" y1="8" x2="22" y2="8" stroke="rgba(124,28,45,0.18)" stroke-width="0.6" stroke-linecap="round"/><line x1="38" y1="8" x2="60" y2="8" stroke="rgba(124,28,45,0.18)" stroke-width="0.6" stroke-linecap="round"/><path d="M30 3 L31 7 L35 8 L31 9 L30 13 L29 9 L25 8 L29 7 Z" fill="rgba(201,169,110,0.3)"/></svg>
        </div>
        <h2 class="script section-title-script reveal">{{ $formBlock['title'] ?? 'Анкета гостя' }}</h2>

        <div class="form-wrap">
            <div id="form-success" class="form-success" style="display:none;">
                <div class="success-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <polyline points="4 12 9 17 20 7" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="cormorant" style="font-size:1.3rem; margin-top:16px; color:var(--dark);">Спасибо! Ваш ответ сохранён.</p>
                <button class="btn-edit jost" id="btn-edit-rsvp">Изменить ответ</button>
            </div>

            <form id="rsvp-form" class="rsvp-form stepper-form">
                @csrf

                <div class="stepper-progress">
                    <div class="stepper-progress-bar" data-step="0"></div>
                </div>

                <div class="stepper-step" data-step="0">
                    <div class="form-group">
                        <label class="form-label jost">Имя Фамилия</label>
                        <input type="text" name="name" class="form-input jost" placeholder="Введите текст" required>
                    </div>
                    <div class="stepper-nav">
                        <button type="button" class="btn-stepper-next btn-submit jost" data-next="1">Далее</button>
                    </div>
                </div>

                <div class="stepper-step" data-step="1" style="display:none;">
                    <div class="form-group">
                        <label class="form-label jost">{{ $ql['attending'] ?? '1. Сможете ли вы присоединиться к нам?' }}</label>
                        <div class="radio-group">
                            @foreach(($fo['attending'] ?? []) as $opt)
                            <label class="radio-option">
                                <input type="radio" name="attending" value="{{ e($opt['value']) }}" @if($loop->first) required @endif>
                                <span class="radio-custom"></span>
                                <span class="jost">{{ $opt['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="stepper-nav">
                        <button type="button" class="btn-stepper-back jost" data-back="0">Назад</button>
                        <button type="button" class="btn-stepper-next btn-submit jost" data-next="2">Далее</button>
                    </div>
                </div>

                <div class="stepper-step" data-step="2" style="display:none;">
                    <div class="form-group">
                        <label class="form-label jost">{{ $ql['food'] ?? '2. Есть ли у вас предпочтения по еде?' }}</label>
                        <div class="radio-group">
                            @foreach(($fo['food'] ?? []) as $opt)
                            <label class="radio-option">
                                <input type="radio" name="food_preference" value="{{ e($opt['value']) }}">
                                <span class="radio-custom"></span>
                                <span class="jost">{{ $opt['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="stepper-nav">
                        <button type="button" class="btn-stepper-back jost" data-back="1">Назад</button>
                        <button type="button" class="btn-stepper-next btn-submit jost" data-next="3">Далее</button>
                    </div>
                </div>

                <div class="stepper-step" data-step="3" style="display:none;">
                    <div class="form-group">
                        <label class="form-label jost">{{ $ql['alcohol'] ?? '3. Какой алкоголь вы предпочитаете?' }}</label>
                        <div class="checkbox-group">
                            @foreach(($fo['alcohol'] ?? []) as $opt)
                            <label class="checkbox-option">
                                <input type="checkbox" name="alcohol_preferences[]" value="{{ e($opt['value']) }}">
                                <span class="checkbox-custom checkbox-box"></span>
                                <span class="jost">{{ $opt['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="stepper-nav">
                        <button type="button" class="btn-stepper-back jost" data-back="2">Назад</button>
                        <button type="button" class="btn-stepper-next btn-submit jost" data-next="4">Далее</button>
                    </div>
                </div>

                <div class="stepper-step" data-step="4" style="display:none;">
                    <div class="form-group">
                        <label class="form-label jost">{{ $ql['allergy'] ?? '4. Пищевая аллергия' }}</label>
                        <textarea name="food_allergy" class="form-input form-textarea jost" placeholder="Введите текст" rows="3"></textarea>
                    </div>
                    <div class="stepper-nav">
                        <button type="button" class="btn-stepper-back jost" data-back="3">Назад</button>
                        <button type="submit" class="btn-submit jost">Отправить</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    @endif


    <!-- Floral divider -->
    @if(($blocks['form']['is_visible'] ?? true))
    <div class="wedding-mini-icon reveal" aria-hidden="true">
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
            {{-- Dove icon --}}
            <path d="M6 18 C6 18 4 14 8 12 C12 10 14 12 14 14 C14 14 16 10 20 10 C24 10 24 14 22 16 L14 22 Z" stroke="rgba(201,169,110,0.3)" stroke-width="0.7" fill="rgba(201,169,110,0.06)"/>
            <circle cx="10" cy="13" r="0.8" fill="rgba(124,28,45,0.2)"/>
            <path d="M3 14 C1 12 2 10 4 11" stroke="rgba(201,169,110,0.25)" stroke-width="0.5" fill="none" stroke-linecap="round"/>
            <path d="M14 22 L14 26" stroke="rgba(201,169,110,0.2)" stroke-width="0.5" stroke-linecap="round"/>
            <path d="M11 26 L17 26" stroke="rgba(201,169,110,0.2)" stroke-width="0.5" stroke-linecap="round"/>
        </svg>
    </div>
    <div class="floral-divider-wrap reveal">
        @include('partials.floral-divider')
    </div>
    @endif

    <!-- ── FOOTER SECTION ── -->
    @if(($blocks['form']['is_visible'] ?? true))
    <section class="section-footer">
        <div class="footer-ornament footer-ornament-tl" aria-hidden="true">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                <path d="M2 46 L2 2 L46 2" stroke="rgba(124,28,45,0.25)" stroke-width="1" stroke-linecap="round"/>
                <circle cx="2" cy="2" r="1.8" fill="rgba(201,169,110,0.42)"/>
                <path d="M10 14 C15 10 19 8 25 8" stroke="rgba(201,169,110,0.35)" stroke-width="0.8" stroke-linecap="round"/>
            </svg>
        </div>
        <div class="footer-ornament footer-ornament-tr" aria-hidden="true">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                <path d="M46 46 L46 2 L2 2" stroke="rgba(124,28,45,0.25)" stroke-width="1" stroke-linecap="round"/>
                <circle cx="46" cy="2" r="1.8" fill="rgba(201,169,110,0.42)"/>
                <path d="M38 14 C33 10 29 8 23 8" stroke="rgba(201,169,110,0.35)" stroke-width="0.8" stroke-linecap="round"/>
            </svg>
        </div>
        <div class="footer-ornament footer-ornament-bl" aria-hidden="true">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                <path d="M2 2 L2 46 L46 46" stroke="rgba(124,28,45,0.2)" stroke-width="1" stroke-linecap="round"/>
                <circle cx="2" cy="46" r="1.8" fill="rgba(201,169,110,0.35)"/>
            </svg>
        </div>
        <div class="footer-ornament footer-ornament-br" aria-hidden="true">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                <path d="M46 2 L46 46 L2 46" stroke="rgba(124,28,45,0.2)" stroke-width="1" stroke-linecap="round"/>
                <circle cx="46" cy="46" r="1.8" fill="rgba(201,169,110,0.35)"/>
            </svg>
        </div>
        <div class="footer-edge footer-edge-top" aria-hidden="true"></div>
        <div class="footer-edge footer-edge-bottom" aria-hidden="true"></div>

        <div class="footer-angel" aria-hidden="true">
            <img src="{{ asset('images/angel.png') }}" alt="" class="footer-angel-image">
        </div>

        <h2 class="script footer-closing" style="opacity:0;">
            {{ $blocks['form']['closing_text'] ?? 'Ждём Вас ♡' }}
        </h2>
        <p class="script footer-subtext" style="opacity:0;">
            {{ $blocks['form']['closing_subtext'] ?? 'С любовью, Ваши жених и невеста' }}
        </p>
        <div class="footer-heart-divider" style="opacity:0;" aria-hidden="true">
            <svg width="120" height="24" viewBox="0 0 120 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <line x1="0" y1="12" x2="38" y2="12" stroke="rgba(201,169,110,0.3)" stroke-width="0.6" stroke-linecap="round"/>
                <line x1="82" y1="12" x2="120" y2="12" stroke="rgba(201,169,110,0.3)" stroke-width="0.6" stroke-linecap="round"/>
                <path d="M60 18 C60 18 51 12.5 51 8.5 C51 6.5 52.5 5 54 5 C55.5 5 57.5 6 60 8.5 C62.5 6 64.5 5 66 5 C67.5 5 69 6.5 69 8.5 C69 12.5 60 18 60 18Z" fill="rgba(201,169,110,0.2)" stroke="rgba(201,169,110,0.35)" stroke-width="0.6"/>
                <circle cx="4" cy="12" r="1.5" fill="rgba(201,169,110,0.3)"/>
                <circle cx="116" cy="12" r="1.5" fill="rgba(201,169,110,0.3)"/>
            </svg>
        </div>
        <div class="footer-names">
            <span class="script footer-name">{{ $blocks['hero']['groom_name'] ?? 'Александр' }}</span>
            <span class="footer-and">&amp;</span>
            <span class="script footer-name">{{ $blocks['hero']['bride_name'] ?? 'Мария' }}</span>
        </div>

        <!-- Bottom botanical line -->
        <div class="footer-botanical">
            @include('partials.floral-divider')
        </div>
    </section>
    @endif

</div><!-- end invitation-wrap -->

<!-- ── RSVP MODAL ── -->
@php
    $formBlock = $formBlock ?? ($blocks['form'] ?? []);
    $fo = $fo ?? ($formBlock['form_options'] ?? []);
    $ql = $ql ?? ($formBlock['question_labels'] ?? []);
@endphp
<div id="rsvp-modal" class="rsvp-modal-overlay" style="display:none;">
    <div class="rsvp-modal">
        <button type="button" class="rsvp-modal-close" id="rsvp-modal-close" aria-label="Закрыть">&times;</button>
        <h3 class="script rsvp-modal-title">Анкета гостя</h3>

        <div class="rsvp-modal-body">
            <div id="modal-form-success" class="form-success" style="display:none;">
                <div class="success-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <polyline points="4 12 9 17 20 7" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="cormorant" style="font-size:1.3rem; margin-top:16px; color:var(--dark);">Спасибо! Ваш ответ сохранён.</p>
                <button class="btn-edit jost" id="btn-edit-modal-rsvp">Изменить ответ</button>
            </div>

            <form id="rsvp-modal-form" class="rsvp-form stepper-form">
                @csrf

                <div class="stepper-progress">
                    <div class="stepper-progress-bar" data-step="0"></div>
                </div>

                <div class="stepper-step" data-step="0">
                    <div class="form-group">
                        <label class="form-label jost">Имя Фамилия</label>
                        <input type="text" name="name" class="form-input jost" placeholder="Введите текст" required>
                    </div>
                    <div class="stepper-nav">
                        <button type="button" class="btn-stepper-next btn-submit jost" data-next="1">Далее</button>
                    </div>
                </div>

                <div class="stepper-step" data-step="1" style="display:none;">
                    <div class="form-group">
                        <label class="form-label jost">{{ $ql['attending'] ?? '1. Сможете ли вы присоединиться к нам?' }}</label>
                        <div class="radio-group">
                            @foreach(($fo['attending'] ?? []) as $opt)
                            <label class="radio-option">
                                <input type="radio" name="attending" value="{{ e($opt['value']) }}" @if($loop->first) required @endif>
                                <span class="radio-custom"></span>
                                <span class="jost">{{ $opt['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="stepper-nav">
                        <button type="button" class="btn-stepper-back jost" data-back="0">Назад</button>
                        <button type="button" class="btn-stepper-next btn-submit jost" data-next="2">Далее</button>
                    </div>
                </div>

                <div class="stepper-step" data-step="2" style="display:none;">
                    <div class="form-group">
                        <label class="form-label jost">{{ $ql['food'] ?? '2. Есть ли у вас предпочтения по еде?' }}</label>
                        <div class="radio-group">
                            @foreach(($fo['food'] ?? []) as $opt)
                            <label class="radio-option">
                                <input type="radio" name="food_preference" value="{{ e($opt['value']) }}">
                                <span class="radio-custom"></span>
                                <span class="jost">{{ $opt['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="stepper-nav">
                        <button type="button" class="btn-stepper-back jost" data-back="1">Назад</button>
                        <button type="button" class="btn-stepper-next btn-submit jost" data-next="3">Далее</button>
                    </div>
                </div>

                <div class="stepper-step" data-step="3" style="display:none;">
                    <div class="form-group">
                        <label class="form-label jost">{{ $ql['alcohol'] ?? '3. Какой алкоголь вы предпочитаете?' }}</label>
                        <div class="checkbox-group">
                            @foreach(($fo['alcohol'] ?? []) as $opt)
                            <label class="checkbox-option">
                                <input type="checkbox" name="alcohol_preferences[]" value="{{ e($opt['value']) }}">
                                <span class="checkbox-custom checkbox-box"></span>
                                <span class="jost">{{ $opt['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="stepper-nav">
                        <button type="button" class="btn-stepper-back jost" data-back="2">Назад</button>
                        <button type="button" class="btn-stepper-next btn-submit jost" data-next="4">Далее</button>
                    </div>
                </div>

                <div class="stepper-step" data-step="4" style="display:none;">
                    <div class="form-group">
                        <label class="form-label jost">{{ $ql['allergy'] ?? '4. Пищевая аллергия' }}</label>
                        <textarea name="food_allergy" class="form-input form-textarea jost" placeholder="Введите текст" rows="3"></textarea>
                    </div>
                    <div class="stepper-nav">
                        <button type="button" class="btn-stepper-back jost" data-back="3">Назад</button>
                        <button type="submit" class="btn-submit jost">Отправить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
