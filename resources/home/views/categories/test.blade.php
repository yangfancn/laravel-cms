@extends('home::layout')

@section('content')
  <div class="flex min-h-[50vh] items-center justify-center">
    <div class="card p-20 shadow-2xl">
      <h1 class="text-primary text-2xl">{{ $category->name }} Page</h1>
    </div>
  </div>
@endsection
