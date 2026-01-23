@php
  $wordCount = str_word_count(strip_tags($post->content ?? ''));
  $readingTime = max(1, (int) ceil($wordCount / 200));
@endphp

@push('scripts')
  @vite(['resources/home/js/Plugins/Comments/comments.ts'])
@endpush

@extends('home::layout')

@section('content')
  <section class="bg-linear-to-b from-base-100 via-base-200/60 to-base-100 relative">
    <div class="hero-pattern pointer-events-none absolute inset-x-0 -top-24 bottom-0"></div>
    <div class="container relative z-10 mx-auto pb-10 pt-10 lg:pt-14">
      <div class="breadcrumbs text-xs">
        <ul>
          <li><a href="{{ route('home') }}">Home</a></li>
          @if ($post->category)
            <li><a href="{{ $post->category->uri }}">{{ $post->category->name }}</a></li>
          @endif
          <li>{{ $post->title }}</li>
        </ul>
      </div>

      <div class="mt-6 grid gap-8 lg:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)]">
        <div class="space-y-5">
          <div class="text-base-content/60 flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.25em]">
            @if ($post->category)
              <a
                href="{{ $post->category->uri }}"
                class="badge badge-outline badge-sm"
              >
                {{ $post->category->name }}
              </a>
            @endif
            <span>{{ $post->created_at->format('M d, Y') }}</span>
            <span class="bg-base-content/40 h-1 w-1 rounded-full"></span>
            <span>{{ $readingTime }} min read</span>
            <span class="bg-base-content/40 h-1 w-1 rounded-full"></span>
            <span>{{ number_format($post->viewCount?->count ?? 0) }} views</span>
          </div>

          <h1 class="font-display text-3xl font-semibold leading-tight md:text-4xl xl:text-5xl">
            {{ $post->title }}
          </h1>

          @if ($post->summary)
            <p class="text-base-content/70 text-lg leading-relaxed">
              {{ $post->summary }}
            </p>
          @endif

          @if ($post->tags->isNotEmpty())
            <div class="flex flex-wrap items-center gap-2 text-sm">
              @foreach ($post->tags as $tag)
                <a
                  href="{{ $tag->uri }}"
                  class="badge badge-ghost badge-sm"
                >
                  {{ $tag->name }}
                </a>
              @endforeach
            </div>
          @endif

          <div class="text-base-content/60 flex flex-wrap items-center gap-4 text-sm">
            @if ($post->updated_at->gt($post->created_at))
              <span>Updated {{ $post->updated_at->diffForHumans() }}</span>
            @endif
          </div>
        </div>

        @if ($post->getFirstMedia('thumb'))
          <div>
            <div class="border-base-200 bg-base-100/90 overflow-hidden rounded-2xl border shadow-sm">
              <x-home::lazy-img
                src="{{ $post->getFirstMedia('thumb')->getUrl() }}"
                alt="{{ $post->title }}"
                class="aspect-4/3 w-full object-cover"
              />
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>

  <section class="container mx-auto mt-10">
    <div class="grid gap-10 lg:grid-cols-[minmax(0,2.2fr)_minmax(0,1fr)] lg:items-start">
      <article class="prose prose-lg prose-headings:font-display prose-a:text-primary ck-content max-w-none">
        {!! $post->content !!}
      </article>

      <aside class="sticky top-20 space-y-6">
        @if ($post->user)
          <div class="border-base-200 bg-linear-to-br from-accent/30 via-teal/10 rounded-2xl border to-yellow-50 p-6 shadow-sm">
            <p class="text-base-content/60 text-xs uppercase tracking-[0.25em]">Author</p>
            <div class="mt-4 flex items-center gap-4">
              <div class="avatar">
                <div class="bg-base-200 text-base-content/70 flex size-14 items-center justify-center overflow-hidden rounded-full">
                  @if ($post->user->photo)
                    <img
                      src="{{ $post->user->photo }}"
                      alt="{{ $post->user->name }}"
                      class="h-full w-full object-cover"
                    />
                  @else
                    <span class="text-lg font-semibold">{{ Str::substr($post->user->name, 0, 1) }}</span>
                  @endif
                </div>
              </div>
              <div>
                <p class="font-medium">
                  <span class="link-hover">{{ $post->user->name }}</span>
                </p>
                <p class="text-base-content/60 text-xs">Staff writer</p>
              </div>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
              <div>
                <p class="text-base-content/60 text-xs uppercase tracking-[0.2em]">Posts</p>
                <p class="font-semibold">{{ number_format($post->user->posts()->count()) }}</p>
              </div>
              <div>
                <p class="text-base-content/60 text-xs uppercase tracking-[0.2em]">Since</p>
                <p class="font-semibold">{{ $post->user->created_at?->format('Y') ?? '—' }}</p>
              </div>
            </div>
          </div>
        @endif

        <div class="border-base-200 bg-linear-to-tr from-base-100 via-base-200/50 to-base-100 rounded-2xl border p-6 shadow-sm">
          <p class="text-base-content/60 text-xs uppercase tracking-[0.25em]">Story details</p>
          <dl class="mt-4 space-y-3 text-sm">
            <div class="flex items-center justify-between gap-4">
              <dt class="text-base-content/60">Published</dt>
              <dd class="font-medium">{{ $post->created_at->format('M d, Y') }}</dd>
            </div>
            @if ($post->updated_at->gt($post->created_at))
              <div class="flex items-center justify-between gap-4">
                <dt class="text-base-content/60">Updated</dt>
                <dd class="font-medium">{{ $post->updated_at->format('M d, Y') }}</dd>
              </div>
            @endif
            <div class="flex items-center justify-between gap-4">
              <dt class="text-base-content/60">Reading time</dt>
              <dd class="font-medium">{{ $readingTime }} min</dd>
            </div>
            @if ($post->category)
              <div class="flex items-center justify-between gap-4">
                <dt class="text-base-content/60">Section</dt>
                <dd class="font-medium">
                  <a
                    href="{{ $post->category->uri }}"
                    class="link link-hover"
                  >{{ $post->category->name }}</a>
                </dd>
              </div>
            @endif
          </dl>
        </div>

        <div class="border-base-200 bg-linear-to-bl from-base-100 via-base-300/30 to-base-100 rounded-2xl border p-6 shadow-sm">
          <p class="text-base-content/60 text-xs uppercase tracking-[0.25em]">Explore</p>
          <div class="mt-4 flex flex-wrap gap-2">
            @if ($post->category)
              <a
                href="{{ $post->category->uri }}"
                class="btn btn-outline btn-sm"
              >More from {{ $post->category->name }}</a>
            @endif
            <a
              href="{{ route('home') }}"
              class="btn btn-ghost btn-sm"
            >Back to home</a>
          </div>
          <p class="text-base-content/60 mt-3 text-xs">Track coverage and updates from our newsroom.</p>
        </div>
      </aside>
    </div>
  </section>

  <section class="xl:mt-15 container mx-auto mt-10">
    <h2 class="font-display text-2xl font-semibold md:text-3xl">Leave your opinion</h2>
    <div id="comment">
      <comments
        :id="@js($post->id)"
        type="post"
      ></comments>
    </div>
  </section>

  @if ($related->isNotEmpty())
    <section class="container mx-auto mt-12">
      <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
        <div>
          <p class="text-base-content/60 text-xs uppercase tracking-[0.35em]">More coverage</p>
          <h2 class="font-display text-2xl font-semibold md:text-3xl">
            More from {{ $post->category?->name ?? 'the newsroom' }}
          </h2>
        </div>
        @if ($post->category)
          <a
            href="{{ $post->category->uri }}"
            class="btn btn-ghost btn-sm"
          >View section</a>
        @endif
      </div>
      <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($related as $item)
          <x-post-list-item
            :post="$item"
            :style="'--delay: ' . (120 + $loop->index * 80) . 'ms'"
          />
        @endforeach
      </div>
    </section>
  @endif
@endsection
