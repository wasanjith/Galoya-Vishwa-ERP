@php
    $routes = \App\Models\Route::query()
        ->withCount('stores')
        ->orderBy('route')
        ->get();
@endphp

@once
    <style>
        .fi-route-cards + .fi-ta {
            display: none !important;
        }
    </style>
@endonce

<div class="fi-route-cards px-4 sm:px-6 lg:px-8">
    <div class="mb-6 rounded-2xl border border-gray-200 bg-white/80 p-6 shadow-sm backdrop-blur dark:border-gray-700 dark:bg-gray-900/70">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-sky-500 dark:text-sky-300">Routes overview</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                    {{ number_format($routes->count()) }} active routes
                </p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Tap any card below to see its shops.
                </p>
            </div>
            
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($routes as $route)
            <div class="rounded-3xl border border-sky-100 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-lg dark:border-gray-700 dark:bg-gray-900 dark:hover:border-sky-500 dark:hover:bg-gray-800">
                <a
                    href="{{ route('filament.admin.resources.stores.index', ['route' => $route->id]) }}"
                    class="group flex w-full flex-col gap-4 text-left focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900"
                >
                    <div class="flex items-start justify-between">
                        <div class="rounded-2xl border border-sky-100 bg-sky-50 p-3 text-sky-500 dark:border-sky-500/30 dark:bg-sky-500/10 dark:text-sky-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9l9-6 9 6-9 6-9-6z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15l9 6 9-6" />
                            </svg>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-semibold uppercase tracking-wide text-sky-500 dark:text-sky-300">Route</span>
                            <p class="mt-1 inline-flex items-center gap-1 rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-600 dark:bg-sky-500/10 dark:text-sky-200">
                                {{ number_format($route->stores_count) }}
                                {{ \Illuminate\Support\Str::plural('store', $route->stores_count) }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900 transition group-hover:text-sky-600 dark:text-white">
                            {{ $route->route }}
                        </p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Click to open the Stores table filtered to this route.
                        </p>
                    </div>
                </a>
                
            </div>
        @empty
            <div class="col-span-full rounded-3xl border border-dashed border-sky-200 bg-white p-8 text-center dark:border-gray-700 dark:bg-gray-900">
                <p class="text-base font-semibold text-gray-900 dark:text-white">No routes yet</p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Use “Add route” to create your first distribution route.
                </p>
            </div>
        @endforelse
    </div>
</div>