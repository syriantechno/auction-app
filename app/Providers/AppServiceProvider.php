<?php

namespace App\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! app()->runningInConsole()) {
            DB::listen(function (QueryExecuted $query): void {
                if ($query->time >= 250) {
                    Log::channel('stack')->warning('Slow database query detected', [
                        'time_ms' => $query->time,
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'connection' => $query->connectionName,
                        'request_path' => request()?->path(),
                        'route_name' => request()?->route()?->getName(),
                    ]);
                }
            });

            // Share Nav Menus with layouts/app
            view()->composer('layouts.app', function ($view) {
                $headerMenu = \App\Models\Menu::where('location', 'header')->with(['items' => function($q) {
                    $q->whereNull('parent_id')->with(['children.page', 'page'])->orderBy('order');
                }])->first();
                
                $footerMenu = \App\Models\Menu::where('location', 'footer')->with(['items' => function($q) {
                    $q->whereNull('parent_id')->with(['children.page', 'page'])->orderBy('order');
                }])->first();

                $view->with('headerMenu', $headerMenu)->with('footerMenu', $footerMenu);
            });
        }
    }
}
