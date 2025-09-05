<div class="border-b-1 border-accent-content/40 py-4">
  <h3 class="mb-2 text-lg font-bold">
    <a href="{{ $post->uri }}">{{ $post->title }}</a>
  </h3>
  <p class="mb-3 opacity-75">{{ $post->summary }}</p>
  <div class="infos flex flex-wrap items-center text-sm">
    <a class="badge badge-accent mr-3" href="{{ $post->category->uri }}">{{ $post->category->name }}</a>
    <span class="opacity-60">{{ $post->created_at->diffForHumans() }}</span>
  </div>
</div>
