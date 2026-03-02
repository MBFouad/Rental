@props(['unit'])

@php
    $images = $unit->getMedia('images');
    $typeColors = [
        'rental' => 'bg-blue-600',
        'sale' => 'bg-green-600',
        'under_construction' => 'bg-amber-500',
    ];
    $typeLabels = [
        'rental' => __('Rental'),
        'sale' => __('Sale'),
        'under_construction' => __('Under Construction'),
    ];
@endphp

<div x-data="{ showQuickView: false, activeImage: 0 }" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden hover:shadow-xl transition-all duration-300 group border border-gray-100 dark:border-gray-700">
    <!-- Image -->
    <div class="relative h-52 bg-gray-200 dark:bg-gray-700 overflow-hidden">
        @if($unit->getFirstMediaUrl('images'))
            <img src="{{ $unit->getFirstMediaUrl('images', 'card') }}" alt="{{ $unit->title }}"
                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">
                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        @endif

        <!-- Overlay Gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

        <!-- Type Badge -->
        <div class="absolute top-3 {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }}">
            <span class="{{ $typeColors[$unit->type] ?? 'bg-gray-500' }} text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg">
                {{ $typeLabels[$unit->type] ?? $unit->type }}
            </span>
        </div>

        <!-- Featured Badge -->
        @if($unit->is_featured)
        <div class="absolute top-3 {{ app()->getLocale() === 'ar' ? 'left-3' : 'right-3' }}">
            <span class="bg-gradient-to-r from-yellow-400 to-amber-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
                {{ __('Featured') }}
            </span>
        </div>
        @endif

        <!-- Quick View Button -->
        @if($images->count() > 0)
        <button @click="showQuickView = true; activeImage = 0"
           class="absolute bottom-3 {{ app()->getLocale() === 'ar' ? 'left-3' : 'right-3' }} bg-white/90 backdrop-blur-sm text-gray-900 text-xs font-semibold px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-white flex items-center gap-1 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            {{ __('Quick View') }}
        </button>
        @endif

        <!-- Image Count Badge -->
        @if($images->count() > 1)
        <div class="absolute bottom-3 {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} bg-black/60 text-white text-xs px-2 py-1 rounded-lg flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            {{ $images->count() }}
        </div>
        @endif
    </div>

    <!-- Content -->
    <div class="p-5">
        <!-- Title -->
        <a href="{{ route('units.show', $unit->slug) }}">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-1 hover:text-blue-600 dark:hover:text-blue-400 transition">
                {{ $unit->title }}
            </h3>
        </a>

        <!-- Location -->
        @if($unit->city || $unit->unitArea || $unit->location)
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-4 flex items-center gap-1.5">
            <svg class="w-4 h-4 flex-shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span class="line-clamp-1">
                @if($unit->unitArea && $unit->city)
                    {{ $unit->unitArea->name }}, {{ $unit->city->name }}
                @elseif($unit->city)
                    {{ $unit->city->name }}
                @else
                    {{ $unit->location }}
                @endif
            </span>
        </p>
        @endif

        <!-- Property Details -->
        <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
            @if($unit->bedrooms)
            <div class="flex items-center gap-1.5" title="{{ __('Bedrooms') }}">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="font-medium">{{ $unit->bedrooms }}</span>
            </div>
            @endif
            @if($unit->bathrooms)
            <div class="flex items-center gap-1.5" title="{{ __('Bathrooms') }}">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                </svg>
                <span class="font-medium">{{ $unit->bathrooms }}</span>
            </div>
            @endif
            @if($unit->area)
            <div class="flex items-center gap-1.5" title="{{ __('Area') }}">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                </svg>
                <span class="font-medium">{{ number_format($unit->area) }} <span class="text-xs">{{ __('sqm') }}</span></span>
            </div>
            @endif
        </div>

        <!-- Price and Action -->
        <div class="flex items-center justify-between">
            <div>
                @if($unit->type === 'rental' && $unit->rentalDetail)
                    <div class="text-xl font-bold text-blue-600 dark:text-blue-400">
                        {{ number_format($unit->rentalDetail->monthly_rent) }} {{ currency_symbol() }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('per month') }}</div>
                @elseif($unit->type === 'sale' && $unit->saleDetail)
                    <div class="text-xl font-bold text-green-600 dark:text-green-400">
                        {{ number_format($unit->saleDetail->sale_price) }} {{ currency_symbol() }}
                    </div>
                    @if($unit->saleDetail->is_negotiable)
                        <div class="text-xs text-green-600 dark:text-green-400 font-medium">{{ __('Negotiable') }}</div>
                    @endif
                @elseif($unit->type === 'under_construction' && $unit->constructionDetail)
                    <div class="text-xl font-bold text-amber-600 dark:text-amber-400">
                        {{ number_format($unit->constructionDetail->total_price) }} {{ currency_symbol() }}
                    </div>
                    @if($unit->constructionDetail->down_payment_percentage)
                        <div class="text-xs text-amber-600 dark:text-amber-400 font-medium">{{ $unit->constructionDetail->down_payment_percentage }}% {{ __('Down Payment') }}</div>
                    @endif
                @endif
            </div>

            <a href="{{ route('units.show', $unit->slug) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 hover:shadow-lg flex items-center gap-1.5">
                {{ __('Details') }}
                <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Quick View Modal -->
    @if($images->count() > 0)
    <div x-show="showQuickView"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90"
         @keydown.escape.window="showQuickView = false"
         @keydown.arrow-right.window="showQuickView && (activeImage = activeImage < {{ $images->count() - 1 }} ? activeImage + 1 : 0)"
         @keydown.arrow-left.window="showQuickView && (activeImage = activeImage > 0 ? activeImage - 1 : {{ $images->count() - 1 }})"
         x-cloak>

        <!-- Close Button -->
        <button @click="showQuickView = false" class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} text-white hover:text-gray-300 transition z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Unit Title -->
        <div class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} text-white z-10">
            <h3 class="text-lg font-bold">{{ $unit->title }}</h3>
            <p class="text-sm text-gray-300">
                @if($unit->unitArea && $unit->city)
                    {{ $unit->unitArea->name }}, {{ $unit->city->name }}
                @elseif($unit->city)
                    {{ $unit->city->name }}
                @endif
            </p>
        </div>

        <!-- Main Image Container -->
        <div class="relative w-full max-w-5xl max-h-[80vh]" @click.outside="showQuickView = false">
            <!-- Images -->
            @foreach($images as $index => $image)
                <img x-show="activeImage === {{ $index }}"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     src="{{ $image->getUrl('banner') }}"
                     alt="{{ $unit->title }}"
                     class="max-w-full max-h-[80vh] mx-auto object-contain rounded-lg">
            @endforeach

            <!-- Navigation Arrows -->
            @if($images->count() > 1)
            <button @click.stop="activeImage = activeImage > 0 ? activeImage - 1 : {{ $images->count() - 1 }}"
                    class="absolute {{ app()->getLocale() === 'ar' ? 'right-0 -mr-4 md:-mr-16' : 'left-0 -ml-4 md:-ml-16' }} top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 text-white w-12 h-12 rounded-full flex items-center justify-center transition">
                <svg class="w-6 h-6 {{ app()->getLocale() === 'ar' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <button @click.stop="activeImage = activeImage < {{ $images->count() - 1 }} ? activeImage + 1 : 0"
                    class="absolute {{ app()->getLocale() === 'ar' ? 'left-0 -ml-4 md:-ml-16' : 'right-0 -mr-4 md:-mr-16' }} top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 text-white w-12 h-12 rounded-full flex items-center justify-center transition">
                <svg class="w-6 h-6 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            @endif

            <!-- Image Counter -->
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black/60 text-white text-sm px-4 py-2 rounded-full">
                <span x-text="activeImage + 1"></span> / {{ $images->count() }}
            </div>
        </div>

        <!-- Thumbnails -->
        @if($images->count() > 1)
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 mt-4 max-w-full overflow-x-auto px-4 pb-2" style="transform: translateX(-50%) translateY(60px);">
            @foreach($images as $index => $image)
                <button @click="activeImage = {{ $index }}"
                        :class="{ 'ring-2 ring-white ring-offset-2 ring-offset-black/50': activeImage === {{ $index }} }"
                        class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden transition opacity-70 hover:opacity-100"
                        :style="activeImage === {{ $index }} ? 'opacity: 1' : ''">
                    <img src="{{ $image->getUrl('thumb') }}" alt="" class="w-full h-full object-cover">
                </button>
            @endforeach
        </div>
        @endif

        <!-- View Details Link -->
        <a href="{{ route('units.show', $unit->slug) }}"
           class="absolute bottom-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition flex items-center gap-2">
            {{ __('View Details') }}
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
    @endif
</div>
