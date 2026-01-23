<!doctype html>
<html
  lang="en"
  data-theme="light"
>

<head>
  <meta charset="UTF-8">
  <meta
    name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
  >
  <meta
    http-equiv="X-UA-Compatible"
    content="ie=edge"
  >
  {!! App\Facades\Seo::generate() !!}
  @vite(['resources/home/js/app.ts', 'resources/home/css/app.css'])
  @stack('scripts')
</head>

<body class="bg-base-100 text-base-content">
  <header
    id="header"
    class="border-base-200 bg-base-100/40 sticky top-0 z-40 backdrop-blur"
  >
    <div
      id="mobile-menu-overlay"
      class="fixed inset-0 z-40 hidden bg-black/50"
    ></div>

    <div class="navbar font-work container mx-auto px-3 lg:px-0">
      {{-- Mobile Drawer --}}
      <div class="navbar-start gap-2">
        <div class="drawer w-auto lg:hidden">
          <input
            id="mobile-drawer"
            type="checkbox"
            class="drawer-toggle"
          />
          <div class="drawer-content">
            <label
              for="mobile-drawer"
              class="btn btn-ghost btn-circle drawer-button lg:hidden"
              aria-label="Open menu"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
              </svg>
            </label>
          </div>

          <div class="drawer-side z-50">
            <label
              for="mobile-drawer"
              class="drawer-overlay transition-opacity duration-300"
            ></label>

            <div class="menu bg-base-100 text-base-content min-h-full w-80 transform p-4 shadow-xl transition-all duration-300">
              <div class="mb-4 flex items-center justify-between">
                <a
                  href="/"
                  class="text-lg font-semibold"
                >{{ $site->name }}</a>
                <label
                  for="mobile-drawer"
                  class="btn btn-circle btn-ghost"
                  aria-label="Close menu"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                </label>
              </div>

              {{-- Mobile Menu (append by javascript) --}}
              <ul
                tabindex="0"
                class="menu mobile w-full"
              >
                <!-- append by javascript -->
              </ul>

              <div class="mt-4 flex flex-wrap items-center gap-2">
                @auth
                  <span class="text-base-content/60 text-xs">
                    Signed in as {{ auth()->user()->name ?? auth()->user()->email }}
                  </span>
                  <form
                    method="POST"
                    action="{{ route('logout') }}"
                  >
                    @csrf
                    @method('DELETE')
                    <button
                      type="submit"
                      class="btn btn-ghost btn-sm"
                    >Logout</button>
                  </form>
                @else
                  <a
                    href="{{ route('login') }}"
                    class="btn btn-ghost btn-sm"
                  >Login</a>
                  <a
                    href="{{ route('sign-up') }}"
                    class="btn btn-primary btn-sm"
                  >Register</a>
                @endauth
              </div>

              <div class="text-base-content/60 mt-4 text-xs">
                <div class="divider my-2"></div>
                <div class="flex flex-wrap gap-3">
                  @foreach ($nav->where('show', false) as $channel)
                    <a
                      href="{{ $channel->uri }}"
                      class="link link-hover"
                    >{{ $channel->name }}</a>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Brand --}}
        <a
          href="/"
          class="flex items-center gap-2"
        >
          <span>{{ $site->name }}</span>
        </a>
      </div>

      {{-- Desktop Nav --}}
      <div class="navbar-center hidden lg:flex">
        <ul class="menu desktop menu-horizontal gap-1 px-1">
          @foreach ($nav as $channel)
            @if ($channel->show)
              @if ($channel->children->count())
                <li>
                  <details>
                    <summary class="font-medium">{{ $channel->name }}</summary>
                    <ul class="bg-base-100 border-base-200 w-56 rounded-xl border p-2 shadow">
                      @foreach ($channel->children as $child)
                        <li><a
                            class="py-2"
                            href="{{ $child->uri }}"
                          >{{ $child->name }}</a></li>
                      @endforeach
                    </ul>
                  </details>
                </li>
              @else
                <li>
                  <a
                    class="font-medium"
                    href="{{ $channel->uri }}"
                  >{{ $channel->name }}</a>
                </li>
              @endif
            @endif
          @endforeach
        </ul>
      </div>

      {{-- Right tools --}}
      <div class="navbar-end gap-2">
        <div class="flex items-center gap-2">
          @auth
            <span class="text-base-content/70 hidden text-sm lg:inline">
              Welcome, {{ auth()->user()->name ?? auth()->user()->email }}
            </span>
            <form
              method="POST"
              action="{{ route('logout') }}"
            >
              @csrf
              @method('DELETE')
              <button
                type="submit"
                class="btn btn-ghost btn-sm"
              >Logout</button>
            </form>
          @else
            <a
              href="{{ route('login') }}"
              class="btn btn-ghost btn-sm"
            >Login</a>
            <a
              href="{{ route('sign-up') }}"
              class="btn btn-primary btn-sm"
            >Register</a>
          @endauth
        </div>

        {{-- Theme Switch (keep your logic) --}}
        <div class="flex-none">
          <div class="dropdown dropdown-end">
            <label
              tabindex="0"
              class="btn btn-ghost btn-circle"
              aria-label="Theme switch"
            >
              <span class="block size-7 rounded-full">
                <svg
                  stroke="currentColor"
                  fill="currentColor"
                  stroke-width="0"
                  viewBox="0 0 512 512"
                  class="text-base-content size-7"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path d="M441 336.2l-.06-.05c-9.93-9.18-22.78-11.34-32.16-12.92l-.69-.12c-9.05-1.49-10.48-2.5-14.58-6.17-2.44-2.17-5.35-5.65-5.35-9.94s2.91-7.77 5.34-9.94l30.28-26.87c25.92-22.91 40.2-53.66 40.2-86.59s-14.25-63.68-40.2-86.6c-35.89-31.59-85-49-138.37-49C223.72 48 162 71.37 116 112.11c-43.87 38.77-68 90.71-68 146.24s24.16 107.47 68 146.23c21.75 19.24 47.49 34.18 76.52 44.42a266.17 266.17 0 0086.87 15h1.81c61 0 119.09-20.57 159.39-56.4 9.7-8.56 15.15-20.83 15.34-34.56.21-14.17-5.37-27.95-14.93-36.84zM112 208a32 32 0 1132 32 32 32 0 01-32-32zm40 135a32 32 0 1132-32 32 32 0 01-32 32zm40-199a32 32 0 1132 32 32 32 0 01-32-32zm64 271a48 48 0 1148-48 48 48 0 01-48 48zm72-239a32 32 0 1132-32 32 32 0 01-32 32z">
                  </path>
                </svg>
              </span>
            </label>
            <ul
              tabindex="0"
              class="dropdown-content bg-base-200 mt-4 grid max-h-80 w-52 overflow-x-auto rounded-xl p-3 shadow-lg"
            >
              <li
                data-set-theme="light"
                data-theme="light"
                class="mb-2 flex w-full cursor-pointer items-center justify-between rounded-lg px-2 py-2 capitalize transition-all duration-300 last-of-type:mb-0"
              >
                <span class="text-base-content flex items-center gap-2">Light</span>
                <div class="flex h-full shrink-0 flex-wrap gap-1">
                  <div class="bg-primary w-2 rounded"></div>
                  <div class="bg-secondary w-2 rounded"></div>
                  <div class="bg-accent w-2 rounded"></div>
                  <div class="bg-neutral w-2 rounded"></div>
                </div>
              </li>
              <li
                data-set-theme="dark"
                data-theme="dark"
                class="mb-2 flex w-full cursor-pointer items-center justify-between rounded-lg px-2 py-2 capitalize transition-all duration-300 last-of-type:mb-0"
              >
                <span class="text-base-content flex items-center gap-2">Dark</span>
                <div class="flex h-full shrink-0 flex-wrap gap-1">
                  <div class="bg-primary w-2 rounded"></div>
                  <div class="bg-secondary w-2 rounded"></div>
                  <div class="bg-accent w-2 rounded"></div>
                  <div class="bg-neutral w-2 rounded"></div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </header>

  <main class="min-h-[60vh]">
    @yield('content')
  </main>

  {{-- Redesigned Footer --}}
  <footer
    id="footer"
    class="border-base-200 bg-base-100 mt-16 border-t"
  >
    <div class="container mx-auto grid gap-10 px-3 py-12 lg:grid-cols-[1.4fr_1fr_1fr_1fr]">
      <div class="space-y-5">
        <a
          href="/"
          class="font-display block text-xl font-semibold"
        >{{ $site->name }}</a>
        <p class="text-base-content/70 text-sm">
          Independent reporting from local desks and national editors, updated around the clock.
        </p>
      </div>

      <div>
        <p class="text-base-content/60 text-xs uppercase tracking-[0.3em]">Sections</p>
        @php
          $footerChannels = $nav->filter(fn($channel) => $channel->show)->take(6);
        @endphp
        <ul class="mt-4 space-y-2 text-sm">
          @foreach ($footerChannels as $channel)
            <li><a
                href="{{ $channel->uri }}"
                class="link link-hover"
              >{{ $channel->name }}</a></li>
          @endforeach
        </ul>
      </div>

      <div>
        <p class="text-base-content/60 text-xs uppercase tracking-[0.3em]">Newsroom</p>
        <ul class="mt-4 space-y-2 text-sm">
          @foreach ($nav->where('show', false) as $channel)
            <li><a
                href="{{ $channel->uri }}"
                class="link link-hover"
              >{{ $channel->name }}</a></li>
          @endforeach
        </ul>
      </div>

      <div>
        <p class="text-base-content/60 text-xs uppercase tracking-[0.3em]">Engage</p>
        <p class="text-base-content/70 mt-4 text-sm">
          Have a story tip? We protect sources and review every submission.
        </p>
        <div class="mt-4 flex flex-wrap gap-2">
          <a
            href="#"
            class="btn btn-outline btn-sm"
          >Submit a tip</a>
          <a
            href="#"
            class="btn btn-ghost btn-sm"
          >Advertise</a>
        </div>
      </div>
    </div>

    <div class="border-base-200 border-t">
      <div class="text-base-content/60 container mx-auto flex flex-wrap items-center justify-between gap-2 px-3 py-4 text-xs">
        <span>Copyright {{ now()->year }} {{ $site->name }}. All rights reserved.</span>
        <div class="flex flex-wrap gap-3">
          <a
            href="{{ route('sitemap') }}"
            class="link link-hover"
          >Sitemap</a>
        </div>
      </div>
    </div>
  </footer>
</body>

</html>
