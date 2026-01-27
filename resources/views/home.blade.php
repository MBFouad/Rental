<x-layouts.app :title="__('Home')">
    <!-- Hero Section with Search -->
    <section class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900 text-white py-24 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMiIvPjwvZz48L2c+PC9zdmc+')] opacity-50"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4">
                    {{ __('Find Your Dream Property') }}
                </h1>
                <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                    {{ __('Discover the best rental, sale, and under-construction properties in Egypt') }}
                </p>
            </div>

            <!-- Search Box -->
            <div class="max-w-4xl mx-auto">
                <form action="{{ route('units.index') }}" method="GET" class="bg-white rounded-2xl shadow-2xl p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <!-- Search Input -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Search') }}</label>
                            <input type="text" name="search" placeholder="{{ __('Search by title, description...') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                        </div>

                        <!-- Type Select -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Type') }}</label>
                            <select name="type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                                <option value="">{{ __('All Types') }}</option>
                                <option value="rental">{{ __('Rental') }}</option>
                                <option value="sale">{{ __('Sale') }}</option>
                                <option value="under_construction">{{ __('Under Construction') }}</option>
                            </select>
                        </div>

                        <!-- City Select -->
                        <div x-data="{ cityId: '', areas: [] }" x-init="$watch('cityId', async (value) => {
                            if (value) {
                                const response = await fetch(`/api/cities/${value}/areas`);
                                areas = await response.json();
                            } else {
                                areas = [];
                            }
                        })">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('City') }}</label>
                            <select name="city_id" x-model="cityId" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                                <option value="">{{ __('All Cities') }}</option>
                                @foreach(\App\Models\City::active()->ordered()->get() as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            {{ __('Search') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Category Cards -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Browse by Category') }}</h2>
                <p class="text-gray-600 dark:text-gray-400">{{ __('Find properties that match your needs') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Rental Card -->
                <a href="{{ route('units.rental') }}" class="group bg-white dark:bg-gray-700 rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-600">
                    <div class="w-16 h-16 mx-auto mb-6 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-2">{{ __('Rental') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-center text-sm">{{ __('Find apartments and villas for rent') }}</p>
                </a>

                <!-- Sale Card -->
                <a href="{{ route('units.sale') }}" class="group bg-white dark:bg-gray-700 rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-600">
                    <div class="w-16 h-16 mx-auto mb-6 bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-2">{{ __('Sale') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-center text-sm">{{ __('Buy your dream home today') }}</p>
                </a>

                <!-- Under Construction Card -->
                <a href="{{ route('units.construction') }}" class="group bg-white dark:bg-gray-700 rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-600">
                    <div class="w-16 h-16 mx-auto mb-6 bg-amber-100 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-2">{{ __('Under Construction') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-center text-sm">{{ __('Invest in upcoming projects') }}</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Properties -->
    @if($featuredUnits->count() > 0)
    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Featured Properties') }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">{{ __('Handpicked properties for you') }}</p>
                </div>
                <a href="{{ route('units.index') }}" class="hidden md:inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 font-medium">
                    {{ __('View All') }}
                    <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredUnits as $unit)
                    <x-unit-card :unit="$unit" />
                @endforeach
            </div>

            <div class="text-center mt-12 md:hidden">
                <a href="{{ route('units.index') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    {{ __('Browse All Properties') }}
                    <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-16 bg-blue-600 dark:bg-blue-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">{{ __('Ready to Find Your Perfect Property?') }}</h2>
            <p class="text-blue-100 mb-8 max-w-2xl mx-auto">{{ __('Browse our extensive collection of properties and find the one that matches your needs.') }}</p>
            <a href="{{ route('units.index') }}" class="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg font-bold hover:bg-blue-50 transition shadow-lg">
                {{ __('Start Browsing') }}
            </a>
        </div>
    </section>
</x-layouts.app>
