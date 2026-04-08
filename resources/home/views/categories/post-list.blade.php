@extends('home::layout')

@section('content')

  <section class="bg-base-100 relative">
    <div class="hero-pattern pointer-events-none absolute inset-x-0 -top-24 bottom-0"></div>
    <div class="container relative z-10 mx-auto pb-8 pt-10 lg:pt-14">
      <div class="breadcrumbs text-xs">
        <ul>
          <li><a href="{{ route('home') }}">Home</a></li>
          @foreach ($category->ancestors()->get() as $ancestor)
            <li><a href="{{ $ancestor->uri }}">{{ $ancestor->name }}</a></li>
          @endforeach
          <li>{{ $category->name }}</li>
        </ul>
      </div>

      <div class="mt-6">
        <p class="text-base-content/60 text-xs uppercase tracking-[0.35em]">Section</p>
        <h1 class="font-display mt-3 text-3xl font-semibold leading-tight md:text-4xl">
          {{ $category->name }}
        </h1>
        <p class="text-base-content/70 mt-3">
          {{ $category->meta?->description ?: 'Latest reporting, local coverage, and data-driven analysis from our newsroom.' }}
        </p>
        <div class="text-base-content/60 mt-4 flex flex-wrap items-center gap-3 text-xs uppercase tracking-[0.2em]">
          <span class="badge badge-outline badge-sm">Page {{ $posts->currentPage() }}</span>
          <span>{{ $posts->count() }} stories this page</span>
          @if ($posts->hasMorePages())
            <span>More updates ahead</span>
          @endif
        </div>
      </div>
    </div>
  </section>

  <section class="container mx-auto mt-10">
    @if ($posts->isEmpty())
      <div class="border-base-300 text-base-content/60 rounded-2xl border border-dashed p-6 text-sm">
        No stories published yet. Check back soon.
      </div>
    @else
      @if ($posts->isNotEmpty())
        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
          @foreach ($posts->slice(0, 1) as $post)
            <x-post-list-item
              :post="$post"
              class="xl:col-span-2 xl:h-full"
              :style="'--delay: ' . (200 + $loop->index * 60) . 'ms'"
            />
          @endforeach
          <x-home::trending :$trending />
          @foreach ($posts->slice(1) as $post)
            <x-post-list-item
              :post="$post"
              :style="'--delay: ' . (200 + $loop->index * 60) . 'ms'"
            />
          @endforeach
        </div>
      @endif
    @endif

    <div class="mt-8">
      {{ $posts->links() }}
    </div>
  </section>
@endsection
