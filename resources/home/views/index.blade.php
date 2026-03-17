@extends('home::layout')

@section('content')
  @php
    $featured = $latest->first();
    $topStories = $latest->slice(1, 2);
    $inFocus = $latest->slice(3, 2);
    $latestList = $latest->slice(5);
  @endphp

  <section class="bg-base-100 relative">
    <div class="hero-pattern pointer-events-none absolute inset-x-0 -top-24 bottom-0"></div>
    <div class="container relative z-10 mx-auto pb-10 pt-10 lg:pt-16">
      <div class="text-base-content/60 mb-6 flex flex-wrap items-center gap-3 text-xs uppercase tracking-[0.35em]">
        <span class="badge badge-error badge-sm tracking-[0.3em]">Live</span>
        <span class="font-semibold">Newsroom Dispatch</span>
        <span class="text-base-content/60 normal-case tracking-normal">
          Independent reporting across the US, updated hourly.
        </span>
      </div>
      <div class="grid gap-8 lg:grid-cols-[minmax(0,2.2fr)_minmax(0,1fr)]">
        <div class="space-y-8">
          @if ($featured)
            <article
              class="card border-base-200 bg-base-100/90 reveal-up border shadow-sm backdrop-blur"
              style="--delay: 0ms;"
            >
              <div class="grid gap-6 p-6 lg:grid-cols-[1.2fr_1fr]">
                <a
                  href="{{ $featured->uri }}"
                  class="group relative overflow-hidden rounded-2xl"
                >
                  <x-home::lazy-img
                    src="{{ $featured->getFirstMedia('thumb')->getUrl() }}"
                    alt="{{ $featured->title }}"
                    class="aspect-5/4 h-full w-full object-cover transition duration-500 group-hover:scale-105"
                  />
                  <div class="bg-linear-to-tr absolute inset-0 from-black/25 via-transparent to-transparent"></div>
                </a>
                <div class="flex flex-col justify-between gap-6">
                  <div>
                    <div class="text-base-content/60 flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.25em]">
                      @if ($featured->category)
                        <a
                          href="{{ $featured->category->uri }}"
                          class="badge badge-outline badge-sm"
                        >
                          {{ $featured->category->name }}
                        </a>
                      @endif
                      <span class="bg-base-content/40 h-1 w-1 rounded-full"></span>
                      <span>{{ $featured->created_at->format('M d, Y') }}</span>
                    </div>
                    <h1 class="font-display mt-4 text-3xl font-semibold leading-tight md:text-4xl">
                      <a
                        href="{{ $featured->uri }}"
                        class="hover:text-primary"
                      >{{ $featured->title }}</a>
                    </h1>
                    @if ($featured->summary)
                      <p class="text-base-content/70 mt-4 line-clamp-3 text-base">
                        {{ $featured->summary }}
                      </p>
                    @endif
                  </div>
                  <div class="text-base-content/60 flex flex-wrap items-center gap-4 text-sm">
                    <a
                      href="{{ $featured->uri }}"
                      class="btn btn-primary btn-sm"
                    >Read story</a>
                    <span>Updated {{ $featured->created_at->diffForHumans() }}</span>
                  </div>
                </div>
              </div>
            </article>
          @endif

          <div class="grid gap-6 md:grid-cols-2">
            @forelse ($topStories as $post)
              <x-home::post-list-item
                :$post
                style="--delay: {{ 120 + $loop->index * 80 }}ms;"
              />
            @empty
              <div class="border-base-300 text-base-content/60 rounded-2xl border border-dashed p-6 text-sm">
                More stories are on the way.
              </div>
            @endforelse
          </div>
        </div>

        <aside class="space-y-6">
          <x-home::trending
            :$trending
            class="lg:flex lg:h-full lg:flex-col lg:justify-center"
          />

          {{-- <div class="border-base-200 bg-base-200/60 reveal-up rounded-2xl border p-6" style="--delay: 200ms;">
            <span class="text-base-content/60 text-xs uppercase tracking-[0.25em]">Newsletter</span>
            <h3 class="font-display mt-2 text-xl font-semibold">Daily Brief</h3>
            <p class="text-base-content/70 mt-2 text-sm">
              The five-minute digest from our editors. Fresh reporting, no noise.
            </p>
            <form class="mt-4 flex flex-col gap-3">
              <input type="email" name="email" placeholder="you@email.com" class="input input-bordered w-full" />
              <button type="submit" class="btn btn-primary btn-sm">Subscribe</button>
            </form>
            <p class="text-base-content/60 mt-3 text-xs">No spam. Unsubscribe anytime.</p>
          </div> --}}
        </aside>
      </div>
    </div>
  </section>

  @if ($inFocus->isNotEmpty())
    <section class="container mx-auto mt-12">
      <div class="mb-6">
        <p class="text-base-content/60 text-xs uppercase tracking-[0.35em]">In Focus</p>
        <h2 class="font-display text-2xl font-semibold md:text-3xl">Deep reporting and analysis</h2>
      </div>
      <div class="grid gap-6 lg:grid-cols-2">
        @foreach ($inFocus as $focus)
          <article
            class="border-base-200 reveal-up min-h-65 group relative overflow-hidden rounded-2xl border shadow-sm"
            style="--delay: {{ 120 + $loop->index * 100 }}ms;"
          >
            <div class="absolute inset-0">
              <x-home::lazy-img
                src="{{ $focus->getFirstMedia('thumb')->getUrl() }}"
                alt="{{ $focus->title }}"
                class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
              />
            </div>
            <div class="bg-linear-to-t absolute inset-0 from-black/80 via-black/35 to-transparent"></div>
            <div class="relative flex h-full flex-col justify-end p-6 text-white">
              <div class="flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.25em] text-white/80">
                @if ($focus->category)
                  <span class="badge badge-outline badge-sm border-white/40 text-white">
                    {{ $focus->category->name }}
                  </span>
                @endif
                <span>{{ $focus->created_at->format('M d, Y') }}</span>
              </div>
              <h3 class="font-display mt-3 text-2xl font-semibold leading-tight">
                <a
                  href="{{ $focus->uri }}"
                  class="transition hover:text-white/80"
                >{{ $focus->title }}</a>
              </h3>
              @if ($focus->summary)
                <p class="mt-2 line-clamp-2 text-sm text-white/80">{{ $focus->summary }}</p>
              @endif
            </div>
          </article>
        @endforeach
      </div>
    </section>
  @endif

  @if ($latestList->isNotEmpty())
    <section class="container mx-auto mt-12">
      <div class="mb-6">
        <p class="text-base-content/60 text-xs uppercase tracking-[0.35em]">Latest Coverage</p>
        <h2 class="font-display text-2xl font-semibold md:text-3xl">On the ground and across the country</h2>
      </div>
      <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($latestList as $post)
          <x-home::post-list-item
            :$post
            style="--delay: {{ 80 + ($loop->index % 6) * 60 }}ms;"
          />
        @endforeach
      </div>
    </section>
  @endif
@endsection
