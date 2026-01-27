@props(['unit'])

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden hover:shadow-xl transition-all duration-300 group border border-gray-100 dark:border-gray-700">
    <!-- Image -->
    <div class="relative h-52 bg-gray-200 dark:bg-gray-700 overflow-hidden">
        @if($unit->getFirstMediaUrl('images'))
            <img src="{{ $unit->getFirstMediaUrl('images') }}" alt="{{ $unit->title }}"
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
            @php
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
        <a href="{{ route('units.show', $unit->slug) }}"
           class="absolute bottom-3 {{ app()->getLocale() === 'ar' ? 'left-3' : 'right-3' }} bg-white/90 backdrop-blur-sm text-gray-900 text-xs font-semibold px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-white flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            {{ __('Quick View') }}
        </a>
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
                        {{ number_format($unit->rentalDetail->monthly_rent) }} {{ __('EGP') }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('per month') }}</div>
                @elseif($unit->type === 'sale' && $unit->saleDetail)
                    <div class="text-xl font-bold text-green-600 dark:text-green-400">
                        {{ number_format($unit->saleDetail->sale_price) }} {{ __('EGP') }}
                    </div>
                    @if($unit->saleDetail->is_negotiable)
                        <div class="text-xs text-green-600 dark:text-green-400 font-medium">{{ __('Negotiable') }}</div>
                    @endif
                @elseif($unit->type === 'under_construction' && $unit->constructionDetail)
                    <div class="text-xl font-bold text-amber-600 dark:text-amber-400">
                        {{ number_format($unit->constructionDetail->total_price) }} {{ __('EGP') }}
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
</div>
