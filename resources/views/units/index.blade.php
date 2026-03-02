<x-layouts.app :title="$pageTitle ?? __('All Units')">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 py-12">
        <div class="max-w-[1536px] mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                {{ $pageTitle ?? __('All Units') }}
            </h1>
            <p class="text-blue-100">
                {{ __(':count properties found', ['count' => $units->total()]) }}
            </p>
        </div>
    </div>

    <div class="max-w-[1536px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:w-80 flex-shrink-0">
                <div x-data="{
                    showFilters: false,
                    cityId: '{{ $filters['city_id'] ?? '' }}',
                    areas: []
                }"
                x-init="
                    if (cityId) {
                        fetch(`/api/cities/${cityId}/areas`)
                            .then(r => r.json())
                            .then(data => areas = data);
                    }
                    $watch('cityId', async (value) => {
                        if (value) {
                            const response = await fetch(`/api/cities/${value}/areas`);
                            areas = await response.json();
                        } else {
                            areas = [];
                        }
                    })
                ">
                    <!-- Mobile Filter Toggle -->
                    <button @click="showFilters = !showFilters"
                            class="lg:hidden w-full bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm flex items-center justify-between mb-4 border border-gray-200 dark:border-gray-700">
                        <span class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            {{ __('Filters') }}
                        </span>
                        <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': showFilters }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Filter Form -->
                    <form action="{{ route('units.index') }}" method="GET"
                          class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden"
                          :class="{ 'hidden lg:block': !showFilters }">

                        <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                {{ __('Filter Properties') }}
                            </h2>
                        </div>

                        <div class="p-5 space-y-5">
                            <!-- Search -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Search') }}</label>
                                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                                       placeholder="{{ __('Search by title, description...') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            </div>

                            <!-- Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Property Type') }}</label>
                                <select name="type" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">{{ __('All Types') }}</option>
                                    <option value="rental" {{ ($filters['type'] ?? '') === 'rental' ? 'selected' : '' }}>{{ __('Rental') }}</option>
                                    <option value="sale" {{ ($filters['type'] ?? '') === 'sale' ? 'selected' : '' }}>{{ __('Sale') }}</option>
                                    <option value="under_construction" {{ ($filters['type'] ?? '') === 'under_construction' ? 'selected' : '' }}>{{ __('Under Construction') }}</option>
                                </select>
                            </div>

                            <!-- City -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('City') }}</label>
                                <select name="city_id" x-model="cityId"
                                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">{{ __('All Cities') }}</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Area -->
                            <div x-show="areas.length > 0" x-cloak>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Area') }}</label>
                                <select name="area_id"
                                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">{{ __('All Areas') }}</option>
                                    <template x-for="area in areas" :key="area.id">
                                        <option :value="area.id" x-text="area.name" :selected="area.id == '{{ $filters['area_id'] ?? '' }}'"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Price Range') }}</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="number" name="price_min" value="{{ $filters['price_min'] ?? '' }}"
                                           placeholder="{{ __('Min') }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <input type="number" name="price_max" value="{{ $filters['price_max'] ?? '' }}"
                                           placeholder="{{ __('Max') }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                </div>
                            </div>

                            <!-- Bedrooms -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Bedrooms') }}</label>
                                <select name="bedrooms" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">{{ __('Any') }}</option>
                                    @for($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" {{ ($filters['bedrooms'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}+</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Bathrooms -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Bathrooms') }}</label>
                                <select name="bathrooms" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">{{ __('Any') }}</option>
                                    @for($i = 1; $i <= 4; $i++)
                                        <option value="{{ $i }}" {{ ($filters['bathrooms'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}+</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Filter Actions -->
                        <div class="p-5 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 space-y-3">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                {{ __('Apply Filters') }}
                            </button>
                            <a href="{{ route('units.index') }}" class="w-full bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                {{ __('Reset Filters') }}
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 min-w-0">
                <!-- Active Filters Tags -->
                @if(array_filter($filters))
                <div class="mb-6 flex flex-wrap gap-2">
                    @if(!empty($filters['type']))
                        <span class="inline-flex items-center gap-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 px-3 py-1.5 rounded-full text-sm">
                            @php
                                $typeLabels = ['rental' => __('Rental'), 'sale' => __('Sale'), 'under_construction' => __('Under Construction')];
                            @endphp
                            {{ $typeLabels[$filters['type']] ?? $filters['type'] }}
                            <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="hover:text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </span>
                    @endif
                    @if(!empty($filters['city_id']))
                        @php $selectedCity = $cities->firstWhere('id', $filters['city_id']); @endphp
                        @if($selectedCity)
                        <span class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 px-3 py-1.5 rounded-full text-sm">
                            {{ $selectedCity->name }}
                            <a href="{{ request()->fullUrlWithQuery(['city_id' => null, 'area_id' => null]) }}" class="hover:text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </span>
                        @endif
                    @endif
                    @if(!empty($filters['search']))
                        <span class="inline-flex items-center gap-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200 px-3 py-1.5 rounded-full text-sm">
                            "{{ $filters['search'] }}"
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="hover:text-purple-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </span>
                    @endif
                    @if(!empty($filters['price_min']) || !empty($filters['price_max']))
                        <span class="inline-flex items-center gap-1 bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200 px-3 py-1.5 rounded-full text-sm">
                            {{ __('Price') }}: {{ $filters['price_min'] ?? '0' }} - {{ $filters['price_max'] ?? '∞' }}
                            <a href="{{ request()->fullUrlWithQuery(['price_min' => null, 'price_max' => null]) }}" class="hover:text-amber-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </span>
                    @endif
                </div>
                @endif

                @if($units->count() > 0)
                    <!-- Units Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($units as $unit)
                            <x-unit-card :unit="$unit" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-10">
                        {{ $units->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-12 text-center">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('No units found') }}</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">{{ __('Try adjusting your filters to find what you\'re looking for.') }}</p>
                        <a href="{{ route('units.index') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            {{ __('Clear All Filters') }}
                        </a>
                    </div>
                @endif
            </main>
        </div>
    </div>
</x-layouts.app>
