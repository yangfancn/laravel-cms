<article
  {{ $attributes->merge(['class' => 'border-base-200 bg-base-100 reveal-up group overflow-hidden rounded-2xl border shadow-sm transition hover:-translate-y-1']) }}
  @if ($style) style="{{ $style }}" @endif
>
  <a
    href="{{ $post->uri }}"
    class="block overflow-hidden"
  >
    <x-home::lazy-img
      src="{{ $post->getFirstMedia('thumb')->getUrl('small') }}"
      alt="{{ $post->title }}"
      class="aspect-4/3 w-full object-cover transition duration-500 group-hover:scale-105"
    />
  </a>
  <div class="p-5">
    <div class="text-base-content/60 flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.2em]">
      @if ($post->relationLoaded('category'))
        <a
          href="{{ $post->category->uri }}"
          class="badge badge-ghost badge-sm"
        >
          {{ $post->category->name }}
        </a>
      @endif
      <span>{{ $post->created_at->diffForHumans() }}</span>
    </div>
    <h3 class="font-display mt-3 line-clamp-2 text-lg font-semibold leading-snug">
      <a
        href="{{ $post->uri }}"
        class="hover:text-primary transition"
      >{{ $post->title }}</a>
    </h3>
    @if ($post->summary)
      <p class="text-base-content/70 mt-2 line-clamp-3 text-sm">{{ $post->summary }}</p>
    @endif
  </div>
</article>
