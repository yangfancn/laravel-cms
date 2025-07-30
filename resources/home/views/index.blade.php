@extends('home::layout')

@section('content')
  <div class="container mx-auto py-5">
    <div class="border border-accent-content/60 rounded-md shadow p-5">
    @foreach($posts as $post)
    <x-post-list-item :post="$post" />
    @endforeach
    </div>
  </div>
@endsection
