@extends("home::layout")

@section("content")
  <div class="container mx-auto py-5">
    <div class="border-accent-content/60 rounded-md border p-5 shadow">
      @foreach ($posts as $post)
        <x-post-list-item :post="$post" />
      @endforeach
    </div>
  </div>
@endsection
