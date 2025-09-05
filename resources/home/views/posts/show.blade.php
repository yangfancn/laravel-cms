@extends("home::layout")

@section("content")
  <div class="container mx-auto py-20">
    <div class="breadcrumbs mb-4 text-sm">
      <ul>
        <li><a href="/">Home</a></li>
        <li><a href="{{ $post->category->uri }}">{{ $post->category->name }}</a></li>
        <li>{{ $post->title }}</li>
      </ul>
    </div>
    <article class="prose post-content ck-content max-w-full">
      <h1>{{ $post->title }}</h1>
      <div class="info flex items-center text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
          class="mr-1 size-4">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>
        <span>{{ $post->viewCount->count }}</span>
        <div class="mx-3 text-gray-600/25">|</div>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
          stroke="currentColor" class="mr-1 size-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span>{{ $post->created_at->format("Y-m-d H:i:s") }}</span>

      </div>
      <hr class="border-t-3">
      {!! $post->content !!}
    </article>
  </div>
@endsection
