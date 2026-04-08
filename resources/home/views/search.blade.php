@extends('home::layout')

@section('content')
  <main class="min-h-[30vh] py-5">
    <section>
      <div class="container mx-auto">
        <div class="bg-base-100 rounded-xl p-5">
          <div class="flex flex-row-reverse items-end justify-between gap-6">
            <h1 class="text-primary text-2xl font-semibold">Results For {{ $q }}</h1>
            <div
              role="tablist"
              class="tabs tabs-border -mx-1 mt-5 font-semibold"
            >
              <a
                href="{{ route('search', ['type' => 'post', 'q' => $q]) }}"
                @class(['tab', 'text-lg', 'tab-active' => $type === 'post'])
              >Posts</a>
            </div>
          </div>

          @if ($posts)
            @if ($posts->isEmpty())
              <div class="text-primary flex min-h-[30vh] flex-col justify-center text-center text-xl font-semibold">
                Empty Results!
              </div>
            @else
              <div class="mt-8 grid gap-6 lg:grid-cols-2">
                @foreach ($posts as $post)
                  <x-post-list-item :$post />
                @endforeach
              </div>
              <div class="mt-8">
                {{ $posts->links() }}
              </div>
            @endif
          @endif
        </div>
      </div>
    </section>
  </main>
@endsection
