<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($movies as $movie)
        <x-movie-card :movie="$movie" />
    @empty
        <div class="col-span-full">
            <div class="bg-white/5 border border-white/10 rounded-xl p-12 text-center">
                <div class="inline-flex flex-col items-center">
                    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-white/80 mb-2">No movies found</h3>
                    <p class="text-sm text-white/50 mb-4">Try adjusting your search criteria or filters</p>
                    <a href="{{ route('movies.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-[#f12711] to-[#f5af19] hover:from-[#d42d02] hover:to-[#e09e00] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#f5af19] focus:ring-offset-[#0a0a0a]">
                        Clear all filters
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if(method_exists($movies, 'hasPages') && $movies->hasPages())
    <div class="mt-8">
        <nav class="flex items-center justify-center space-x-2" aria-label="Pagination">
            <div class="relative inline-flex rounded-md shadow-sm">
                {{ $movies->appends(request()->query())->links() }}
            </div>
        </nav>
        <div class="mt-4 text-sm text-white/50 text-center">
            Showing {{ $movies->firstItem() }} to {{ $movies->lastItem() }} of {{ $movies->total() }} results
        </div>
    </div>
@endif
