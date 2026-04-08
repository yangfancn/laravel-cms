@extends('home::layout')

@section('content')
  <section class="bg-base-100 relative">
    <div class="hero-pattern pointer-events-none absolute inset-x-0 -top-24 bottom-0"></div>
    <div class="container relative z-10 mx-auto pb-10 pt-10 lg:pt-14">
      <div class="breadcrumbs text-xs">
        <ul>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li>Topics</li>
          <li>{{ $tag->name }}</li>
        </ul>
      </div>

      <div class="mt-6">
        <p class="text-base-content/60 text-xs uppercase tracking-[0.35em]">Topic</p>
        <h1 class="font-display mt-3 text-3xl font-semibold leading-tight md:text-4xl">
          {{ $tag->name }}
        </h1>
        <p class="text-base-content/70 mt-3 text-lg">{{ $description }}</p>
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
        No stories have been tagged yet. Check back soon.
      </div>
    @else
      <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($posts as $post)
          <x-home::post-list-item :$post style="--delay: {{ 80 + $loop->index * 60 }}ms;" />
        @endforeach
      </div>
    @endif

    <div class="mt-8">
      {{ $posts->links() }}
    </div>
  </section>
@endsection
