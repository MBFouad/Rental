<x-layouts.app :title="$unit->title">
    <!-- Breadcrumb -->
    <div class="bg-gray-100 dark:bg-gray-800 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">{{ __('Home') }}</a>
                <svg class="w-4 h-4 text-gray-400 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('units.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">{{ __('Properties') }}</a>
                <svg class="w-4 h-4 text-gray-400 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900 dark:text-white font-medium truncate max-w-xs">{{ $unit->title }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Image Gallery -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm">
                    @php $images = $unit->getMedia('images'); @endphp
                    @if($images->count() > 0)
                        <div x-data="{ activeImage: 0, fullscreen: false }" class="relative">
                            <!-- Main Image -->
                            <div class="aspect-[16/10] bg-gray-200 dark:bg-gray-700 relative cursor-pointer" @click="fullscreen = true">
                                @foreach($images as $index => $image)
                                    <img x-show="activeImage === {{ $index }}"
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         src="{{ $image->getUrl('banner') }}"
                                         alt="{{ $unit->title }}"
                                         class="w-full h-full object-cover">
                                @endforeach

                                <!-- Navigation Arrows -->
                                @if($images->count() > 1)
                                <button @click.stop="activeImage = activeImage > 0 ? activeImage - 1 : {{ $images->count() - 1 }}"
                                        class="absolute {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition">
                                    <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                <button @click.stop="activeImage = activeImage < {{ $images->count() - 1 }} ? activeImage + 1 : 0"
                                        class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition">
                                    <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                @endif

                                <!-- Image Counter -->
                                <div class="absolute bottom-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} bg-black/60 text-white text-sm px-3 py-1.5 rounded-lg">
                                    <span x-text="activeImage + 1"></span> / {{ $images->count() }}
                                </div>

                                <!-- Fullscreen Icon -->
                                <div class="absolute bottom-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} bg-black/60 text-white text-sm px-3 py-1.5 rounded-lg flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Thumbnails -->
                            @if($images->count() > 1)
                            <div class="flex gap-2 p-4 overflow-x-auto">
                                @foreach($images as $index => $image)
                                    <button @click="activeImage = {{ $index }}"
                                            :class="{ 'ring-2 ring-blue-500 ring-offset-2': activeImage === {{ $index }} }"
                                            class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden transition">
                                        <img src="{{ $image->getUrl('thumb') }}" alt=""
                                             class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                            @endif

                            <!-- Fullscreen Modal -->
                            <div x-show="fullscreen"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center"
                                 @keydown.escape.window="fullscreen = false"
                                 x-cloak>
                                <button @click="fullscreen = false" class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} text-white hover:text-gray-300 transition">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                                <div class="max-w-6xl max-h-[90vh] mx-4">
                                    @foreach($images as $index => $image)
                                        <img x-show="activeImage === {{ $index }}"
                                             src="{{ $image->getUrl() }}"
                                             alt="{{ $unit->title }}"
                                             class="max-w-full max-h-[90vh] object-contain">
                                    @endforeach
                                </div>
                                @if($images->count() > 1)
                                <button @click="activeImage = activeImage > 0 ? activeImage - 1 : {{ $images->count() - 1 }}"
                                        class="absolute {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} top-1/2 -translate-y-1/2 text-white hover:text-gray-300 transition">
                                    <svg class="w-12 h-12 {{ app()->getLocale() === 'ar' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                <button @click="activeImage = activeImage < {{ $images->count() - 1 }} ? activeImage + 1 : 0"
                                        class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-1/2 -translate-y-1/2 text-white hover:text-gray-300 transition">
                                    <svg class="w-12 h-12 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="aspect-[16/10] bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Title & Location (Mobile) -->
                <div class="lg:hidden bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm">
                    @php
                        $typeColors = [
                            'rental' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                            'sale' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
                            'under_construction' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200',
                        ];
                        $typeLabels = [
                            'rental' => __('Rental'),
                            'sale' => __('Sale'),
                            'under_construction' => __('Under Construction'),
                        ];
                    @endphp
                    <span class="{{ $typeColors[$unit->type] ?? 'bg-gray-100 text-gray-800' }} text-sm font-semibold px-3 py-1 rounded-full inline-block mb-3">
                        {{ $typeLabels[$unit->type] ?? $unit->type }}
                    </span>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $unit->title }}</h1>
                    @if($unit->city || $unit->unitArea)
                    <p class="text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        @if($unit->unitArea && $unit->city)
                            {{ $unit->unitArea->name }}, {{ $unit->city->name }}
                        @elseif($unit->city)
                            {{ $unit->city->name }}
                        @endif
                    </p>
                    @endif
                    @if($unit->location)
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                        {{ $unit->location }}
                    </p>
                    @endif
                </div>

                <!-- Description -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ __('Description') }}
                    </h2>
                    <div class="text-gray-600 dark:text-gray-300 prose dark:prose-invert max-w-none leading-relaxed">
                        {!! nl2br(e($unit->description)) !!}
                    </div>
                </div>

                <!-- Property Features -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        {{ __('Property Features') }}
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if($unit->bedrooms)
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-center">
                            <div class="w-12 h-12 mx-auto mb-3 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $unit->bedrooms }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Bedrooms') }}</div>
                        </div>
                        @endif
                        @if($unit->bathrooms)
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-center">
                            <div class="w-12 h-12 mx-auto mb-3 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $unit->bathrooms }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Bathrooms') }}</div>
                        </div>
                        @endif
                        @if($unit->area)
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-center">
                            <div class="w-12 h-12 mx-auto mb-3 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($unit->area) }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('sqm') }}</div>
                        </div>
                        @endif
                        @if($unit->floor)
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-center">
                            <div class="w-12 h-12 mx-auto mb-3 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $unit->floor }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Floor') }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Videos -->
                @php $videos = $unit->getMedia('videos'); @endphp
                @if($videos->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        {{ __('Videos') }}
                    </h2>
                    <div class="space-y-4">
                        @foreach($videos as $video)
                            <video controls class="w-full rounded-xl">
                                <source src="{{ $video->getUrl() }}" type="{{ $video->mime_type }}">
                            </video>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    <!-- Unit Info Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm">
                        <!-- Type Badge (Desktop) -->
                        <div class="hidden lg:block mb-4">
                            @php
                                $typeColors = [
                                    'rental' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                                    'sale' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
                                    'under_construction' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200',
                                ];
                                $typeLabels = [
                                    'rental' => __('Rental'),
                                    'sale' => __('Sale'),
                                    'under_construction' => __('Under Construction'),
                                ];
                            @endphp
                            <span class="{{ $typeColors[$unit->type] ?? 'bg-gray-100 text-gray-800' }} text-sm font-semibold px-3 py-1 rounded-full">
                                {{ $typeLabels[$unit->type] ?? $unit->type }}
                            </span>
                            @if($unit->is_featured)
                                <span class="bg-gradient-to-r from-yellow-400 to-amber-500 text-white text-sm font-semibold px-3 py-1 rounded-full ms-2">
                                    {{ __('Featured') }}
                                </span>
                            @endif
                        </div>

                        <h1 class="hidden lg:block text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $unit->title }}</h1>

                        @if($unit->city || $unit->unitArea)
                        <p class="hidden lg:flex text-gray-500 dark:text-gray-400 items-center gap-1.5 mb-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            @if($unit->unitArea && $unit->city)
                                {{ $unit->unitArea->name }}, {{ $unit->city->name }}
                            @elseif($unit->city)
                                {{ $unit->city->name }}
                            @endif
                        </p>
                        @endif
                        @if($unit->location)
                        <p class="hidden lg:block text-gray-500 dark:text-gray-400 text-sm mb-6">
                            {{ $unit->location }}
                        </p>
                        @elseif($unit->city || $unit->unitArea)
                        <div class="hidden lg:block mb-4"></div>
                        @endif

                        <!-- Price -->
                        <div class="border-t border-gray-100 dark:border-gray-700 py-6">
                            @if($unit->type === 'rental' && $unit->rentalDetail)
                                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ number_format($unit->rentalDetail->monthly_rent) }} {{ currency_symbol() }}
                                </div>
                                <div class="text-gray-500 dark:text-gray-400">{{ __('per month') }}</div>
                                @if($unit->rentalDetail->insurance_amount)
                                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                        <span class="font-medium">{{ __('Insurance Amount') }}:</span>
                                        <span class="text-blue-600 dark:text-blue-400 font-semibold">{{ number_format($unit->rentalDetail->insurance_amount) }} {{ currency_symbol() }}</span>
                                    </div>
                                @endif
                            @elseif($unit->type === 'sale' && $unit->saleDetail)
                                <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                                    {{ number_format($unit->saleDetail->sale_price) }} {{ currency_symbol() }}
                                </div>
                                @if($unit->saleDetail->is_negotiable)
                                    <div class="text-sm text-green-600 dark:text-green-400 font-medium flex items-center gap-1 mt-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ __('Negotiable') }}
                                    </div>
                                @endif
                            @elseif($unit->type === 'under_construction' && $unit->constructionDetail)
                                <div class="text-3xl font-bold text-amber-600 dark:text-amber-400">
                                    {{ number_format($unit->constructionDetail->total_price) }} {{ currency_symbol() }}
                                </div>
                                @if($unit->constructionDetail->down_payment_amount)
                                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-400 bg-amber-50 dark:bg-amber-900/20 rounded-lg p-3">
                                        <span class="font-medium">{{ __('Down Payment') }}:</span>
                                        <span class="text-amber-600 dark:text-amber-400 font-semibold">{{ number_format($unit->constructionDetail->down_payment_amount) }} {{ currency_symbol() }}</span>
                                        @if($unit->constructionDetail->down_payment_percentage)
                                            <span class="text-gray-500">({{ $unit->constructionDetail->down_payment_percentage }}%)</span>
                                        @endif
                                    </div>
                                @endif
                                @if($unit->constructionDetail->expected_completion)
                                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ __('Expected Completion') }}: <span class="font-semibold">{{ $unit->constructionDetail->expected_completion->format('M Y') }}</span>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Payment Plans (for under construction) -->
                        @if($unit->type === 'under_construction' && $unit->constructionDetail && $unit->constructionDetail->paymentPlans->count() > 0)
                        <div class="border-t border-gray-100 dark:border-gray-700 py-6">
                            <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ __('Payment Plans') }}
                            </h3>
                            <div class="space-y-3">
                                @foreach($unit->constructionDetail->paymentPlans as $plan)
                                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl p-4 flex justify-between items-center">
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-white">{{ $plan->duration_years }} {{ __('Years') }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="text-lg font-bold text-amber-600 dark:text-amber-400">{{ number_format($plan->monthly_installment) }} {{ currency_symbol() }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('per month') }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- I am Interested Button -->
                        <div x-data="{ showInquiryModal: false, submitted: false, loading: false, errors: {} }">
                            <button @click="showInquiryModal = true"
                                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                {{ __('I am Interested') }}
                            </button>

                            <!-- Inquiry Modal -->
                            <div x-show="showInquiryModal"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                                 @keydown.escape.window="showInquiryModal = false"
                                 x-cloak>
                                <div x-show="showInquiryModal"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     @click.outside="showInquiryModal = false"
                                     class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

                                    <!-- Success State -->
                                    <div x-show="submitted" class="p-8 text-center">
                                        <div class="w-20 h-20 mx-auto mb-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                            <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Thank You!') }}</h3>
                                        <p class="text-gray-600 dark:text-gray-400 mb-6">{{ __('Your inquiry has been submitted successfully. We will contact you soon.') }}</p>
                                        <button @click="showInquiryModal = false; submitted = false"
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                                            {{ __('Close') }}
                                        </button>
                                    </div>

                                    <!-- Form State -->
                                    <div x-show="!submitted">
                                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Send Inquiry') }}</h3>
                                            <button @click="showInquiryModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <form @submit.prevent="
                                            loading = true;
                                            errors = {};
                                            fetch('{{ route('units.inquiry', $unit) }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'Accept': 'application/json'
                                                },
                                                body: JSON.stringify({
                                                    name: $refs.name.value,
                                                    phone: $refs.phone.value,
                                                    email: $refs.email.value,
                                                    message: $refs.message.value
                                                })
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                loading = false;
                                                if (data.errors) {
                                                    errors = data.errors;
                                                } else if (data.success) {
                                                    submitted = true;
                                                }
                                            })
                                            .catch(() => {
                                                loading = false;
                                            })
                                        " class="p-6 space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Your Name') }} <span class="text-red-500">*</span></label>
                                                <input type="text" x-ref="name" required
                                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                                       :class="{ 'border-red-500': errors.name }"
                                                       placeholder="{{ __('Enter your name') }}">
                                                <p x-show="errors.name" x-text="errors.name?.[0]" class="mt-1 text-sm text-red-500"></p>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Phone Number') }} <span class="text-red-500">*</span></label>
                                                <input type="tel" x-ref="phone" required
                                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                                       :class="{ 'border-red-500': errors.phone }"
                                                       placeholder="{{ __('Enter your phone number') }}">
                                                <p x-show="errors.phone" x-text="errors.phone?.[0]" class="mt-1 text-sm text-red-500"></p>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Email') }} <span class="text-gray-400">({{ __('Optional') }})</span></label>
                                                <input type="email" x-ref="email"
                                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                                       :class="{ 'border-red-500': errors.email }"
                                                       placeholder="{{ __('Enter your email') }}">
                                                <p x-show="errors.email" x-text="errors.email?.[0]" class="mt-1 text-sm text-red-500"></p>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Message') }} <span class="text-gray-400">({{ __('Optional') }})</span></label>
                                                <textarea x-ref="message" rows="3"
                                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none"
                                                          placeholder="{{ __('Write your message here...') }}"></textarea>
                                            </div>

                                            <button type="submit"
                                                    :disabled="loading"
                                                    class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-bold py-4 rounded-lg transition flex items-center justify-center gap-2">
                                                <svg x-show="loading" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span x-text="loading ? '{{ __('Sending...') }}' : '{{ __('Send Inquiry') }}'"></span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    @if(setting_array('phone_numbers') || setting_array('whatsapp_numbers'))
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ __('Contact Us') }}
                        </h3>
                        <div class="space-y-3">
                            @foreach(setting_array('phone_numbers') ?? [] as $phone)
                            <a href="tel:{{ $phone }}" class="flex items-center gap-3 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <span dir="ltr">{{ $phone }}</span>
                            </a>
                            @endforeach

                            @foreach(setting_array('whatsapp_numbers') ?? [] as $whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank"
                               class="flex items-center gap-3 text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                </div>
                                <span dir="ltr">{{ $whatsapp }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
