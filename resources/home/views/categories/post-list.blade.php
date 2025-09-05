@extends("home::layout")

@section("content")
  <div class="container mx-auto py-5">
    <div class="border-accent-content/60 rounded-md border p-5 shadow">
      <div class="breadcrumbs text-sm">
        <ul>
          <li><a href="/">Home</a></li>
          <li>{{ $category->name ?? "search" }}</li>
        </ul>
      </div>
      @foreach ($posts as $post)
        <x-post-list-item :post="$post" />
      @endforeach
      <div class="mt-5">
        {{ $posts->links() }}
      </div>
    </div>
  </div>
@endsection
