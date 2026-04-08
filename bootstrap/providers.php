<?php

use App\Providers\AppServiceProvider;
use App\Providers\CookiesServiceProvider;
use App\Providers\InertiaMessageServiceProvider;
use App\Providers\SlugServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use Torann\GeoIP\GeoIPServiceProvider;

return [
    AppServiceProvider::class,
    InertiaMessageServiceProvider::class,
    SlugServiceProvider::class,
    PermissionServiceProvider::class,
    GeoIPServiceProvider::class,
    CookiesServiceProvider::class,
];
