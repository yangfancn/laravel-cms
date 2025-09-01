<!doctype html>
<html lang="en" data-theme="light">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  {!! App\Facades\Seo::generate() !!}
  @vite(["resources/home/js/app.ts", "resources/home/css/app.css"])
  @stack("scripts")
</head>

<body>
  <header id="header" class="border-b-1">
    <div id="mobile-menu-overlay" class="fixed inset-0 z-40 hidden bg-black/50"></div>
    <div class="font-work navbar container mx-auto">
      <div class="drawer w-auto lg:hidden">
        <input id="mobile-drawer" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content">
          <!-- 汉堡菜单按钮 -->
          <label for="mobile-drawer" class="btn btn-ghost btn-circle drawer-button lg:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </label>
        </div>
        <!-- 侧边抽屉内容 -->
        <div class="drawer-side z-50">
          <label for="mobile-drawer" class="drawer-overlay transition-opacity duration-300"></label>
          <div
            class="menu bg-base-100 text-base-content min-h-full w-80 transform p-4 shadow-lg transition-all duration-300">
            <!-- 关闭按钮 -->
            <div class="mb-4 flex justify-end">
              <label for="mobile-drawer" class="btn btn-circle btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </label>
            </div>
            <!-- mobile menu -->
            <ul tabindex="0" class="menu mobile w-full">
              <!-- append by javascript -->
            </ul>
          </div>
        </div>
      </div>
      <div class="navbar-start">
        <a href="/" class="text-xl font-bold">{{ $site->name }}</a>
      </div>
      <div class="navbar-center hidden lg:flex">
        <ul class="menu desktop menu-horizontal px-1">
          @foreach ($nav as $channel)
            @if ($channel->show)
              @if ($channel->children->count())
                <li>
                  <details>
                    <summary>{{ $channel->name }}</summary>
                    <ul class="p-2">
                      @foreach ($channel->children as $child)
                        <li><a href="{{ $child->uri }}">{{ $child->name }}</a></li>
                      @endforeach
                    </ul>
                  </details>
                </li>
              @else
                <li><a href="{{ $channel->uri }}">{{ $channel->name }}</a></li>
              @endif
            @endif
          @endforeach
        </ul>
      </div>
      <div class="navbar-end">
        <div class="col-span-9 flex items-center justify-end gap-10 xl:col-span-3 xl:justify-center">
          <!--   Search section	-->
          <form action="/search">
            <div class="bg-base-200 hidden items-center gap-4 rounded-md py-2 pl-4 pr-3 sm:flex">
              <input type="text" name="search"
                class="text-base-content bg-base-200 placeholder:font-work w-28 outline-none" placeholder="Search" />
              <div>
                <svg class="cursor-pointer" width="16" height="16" viewBox="0 0 16 16" fill="none"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M6.90906 2C5.93814 2 4.98903 2.28791 4.18174 2.82733C3.37444 3.36674 2.74524 4.13343 2.37368 5.03045C2.00213 5.92746 1.90491 6.91451 2.09433 7.86677C2.28375 8.81904 2.75129 9.69375 3.43783 10.3803C4.12438 11.0668 4.99909 11.5344 5.95135 11.7238C6.90362 11.9132 7.89067 11.816 8.78768 11.4444C9.6847 11.0729 10.4514 10.4437 10.9908 9.63639C11.5302 8.8291 11.8181 7.87998 11.8181 6.90906C11.818 5.60712 11.3008 4.35853 10.3802 3.43792C9.45959 2.51731 8.211 2.00008 6.90906 2Z"
                    stroke="#52525B" stroke-width="1.5" stroke-miterlimit="10" />
                  <path d="M10.5715 10.5716L14 14" stroke="#52525B" stroke-width="1.5" stroke-miterlimit="10"
                    stroke-linecap="round" />
                </svg>
              </div>
            </div>
          </form>
          <!--	Theme Switch	-->
          <div class="flex-none">
            <div class="dropdown dropdown-end">
              <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                <span class="block w-7 rounded-full">
                  <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512"
                    class="text-base-content h-7 w-7" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M441 336.2l-.06-.05c-9.93-9.18-22.78-11.34-32.16-12.92l-.69-.12c-9.05-1.49-10.48-2.5-14.58-6.17-2.44-2.17-5.35-5.65-5.35-9.94s2.91-7.77 5.34-9.94l30.28-26.87c25.92-22.91 40.2-53.66 40.2-86.59s-14.25-63.68-40.2-86.6c-35.89-31.59-85-49-138.37-49C223.72 48 162 71.37 116 112.11c-43.87 38.77-68 90.71-68 146.24s24.16 107.47 68 146.23c21.75 19.24 47.49 34.18 76.52 44.42a266.17 266.17 0 0086.87 15h1.81c61 0 119.09-20.57 159.39-56.4 9.7-8.56 15.15-20.83 15.34-34.56.21-14.17-5.37-27.95-14.93-36.84zM112 208a32 32 0 1132 32 32 32 0 01-32-32zm40 135a32 32 0 1132-32 32 32 0 01-32 32zm40-199a32 32 0 1132 32 32 32 0 01-32-32zm64 271a48 48 0 1148-48 48 48 0 01-48 48zm72-239a32 32 0 1132-32 32 32 0 01-32 32z">
                    </path>
                  </svg>
                </span>
              </label>
              <ul tabindex="0"
                class="dropdown-content bg-base-200 mt-5 grid max-h-80 w-52 overflow-x-auto rounded-lg p-3 shadow-lg">
                <li data-set-theme="light" data-theme="light"
                  class="mb-2 flex w-full cursor-pointer items-center justify-between rounded-md px-2 py-2 capitalize transition-all duration-300 last-of-type:mb-0">
                  <span class="text-base-content flex items-center gap-2">
                    Light
                  </span>
                  <div class="flex h-full flex-shrink-0 flex-wrap gap-1">
                    <div class="bg-primary w-2 rounded"></div>
                    <div class="bg-secondary w-2 rounded"></div>
                    <div class="bg-accent w-2 rounded"></div>
                    <div class="bg-neutral w-2 rounded"></div>
                  </div>
                </li>
                <li data-set-theme="dark" data-theme="dark"
                  class="mb-2 flex w-full cursor-pointer items-center justify-between rounded-md px-2 py-2 capitalize transition-all duration-300 last-of-type:mb-0">
                  <span class="text-base-content flex items-center gap-2">
                    Dark
                  </span>
                  <div class="flex h-full flex-shrink-0 flex-wrap gap-1">
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
    </div>
  </header>
  <div class="mb-4 ml-4 flex h-24 flex-col border-2 border-gray-300 p-3 text-3xl text-gray-700 shadow-md">
    <h1>{{ $site->name }}</h1>
  </div>
  @yield("content")
  <footer id="footer">
    <div class="footer bg-base-200 mt-24 px-5 font-sans md:px-0">
      <div class="container mx-auto">
        <div
          class="bg-base-200 border-base-content/10 flex w-full flex-col items-end justify-between gap-4 border-t py-8 md:flex-row md:gap-5">
          <div class="flex items-center gap-2.5">
            <div>
              <h4 class="text-base-content font-sans text-xl">{{ $site->name }}</h4>
              <p class="text-base-content/70 mt-0.5 text-sm">© 2025 DaisyUI + Laravel. Power By YF.</p>
            </div>
          </div>
          <div class="text-base-content/70 flex items-center gap-4">
            <a href="https://beian.miit.gov.cn/">鄂ICP备18026894号-2</a>
            <a target="_blank" class="flex"
              href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=44030702005295">
              <img src="{{ Vite::asset("resources/home/images/beian.png") }}" alt="" class="mr-1">
              <span>粤公网安备 44030702005295号</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </footer>
</body>

</html>
