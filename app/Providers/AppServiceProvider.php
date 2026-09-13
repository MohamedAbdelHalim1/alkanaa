<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
      Schema::defaultStringLength(191);
      Paginator::useBootstrap();

      // index.php sits at the project root (not in public/), so the web
      // root IS the project root and everything under public/ is served
      // at /public/... — the same convention static_asset() already uses
      // everywhere else. Laravel's Vite helper defaults to emitting
      // asset('build/...'), which resolves to /build/... and 404s here.
      // Mirror the codebase-wide public/-prefixed convention instead.
      Vite::useBuildDirectory('build')
          ->createAssetPathsUsing(
              fn (string $path, ?bool $secure = null) => static_asset(ltrim($path, '/'), $secure)
          );

      View::composer('*', function ($view) {
        $categories = \Cache::rememberForever('header_categories', function () {
            return Category::all();
        });

        $view->with('categories', $categories);
    });
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}
