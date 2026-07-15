<div class="flex gap-4 overflow-x-auto pb-4 scrollbar-thin snap-x snap-mandatory" style="scrollbar-width: thin;">
    @forelse($related as $movie)
        <div class="flex-shrink-0 w-44 snap-start">
            <x-movie-card :movie="$movie" />
        </div>
    @empty
        <div class="flex-1 text-center py-8 text-white/40">
            No related movies found.
        </div>
    @endforelse
</div>
