<div {{ $attributes->merge(['class' => 'border-base-200 bg-base-100/80 reveal-up rounded-2xl border p-6 shadow-sm']) }} style="--delay: 120ms;">
  <div class="flex items-center justify-between">
    <h2 class="font-display text-lg font-semibold">Trending now</h2>
  </div>
  <div class="mt-4 space-y-4">
    @forelse ($trending as $index => $trend)
      <div class="flex items-start gap-3">
        <span class="font-display text-primary text-lg">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
        <div>
          <a href="{{ $trend->uri }}" class="hover:text-primary font-semibold leading-snug">
            {{ $trend->title }}
          </a>
          <div class="text-base-content/60 mt-1 flex flex-wrap items-center gap-2 text-xs">
            @if ($trend->relationLoaded('category'))
              <a href="{{ $trend->category->uri }}" class="badge badge-outline badge-xs">
                {{ $trend->category->name }}
              </a>
            @endif
            @if ($trend->relationLoaded('user'))
              <a href="{{ $trend->user->uri }}" class="badge badge-info badge-outline badge-xs">
                {{ $trend->user->name }}
              </a>
            @endif
            <span>{{ $trend->created_at->format('M d') }}</span>
          </div>
        </div>
      </div>
    @empty
      <p class="text-base-content/60 text-sm">No trending stories yet.</p>
    @endforelse
  </div>
</div>
