<img
  src="{{ Vite::asset('resources/home/images/placeholder.svg') }}"
  data-src="{{ $src }}"
  alt="{{ $alt }}"
  {{ $attributes->except(['src', 'alt'])->merge(['class' => 'lazy']) }}
>
