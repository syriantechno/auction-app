<?php

namespace App\Providers;

use App\Helpers\CurrencyHelper;
use App\Models\SystemSetting;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (app()->runningInConsole()) return;

        // ── 1. Slow query logger (dev only) ─────────────────────────
        if (config('app.debug')) {
            DB::listen(function (QueryExecuted $query): void {
                if ($query->time >= 300) {
                    Log::warning('Slow query', [
                        'time_ms' => $query->time,
                        'sql'     => $query->sql,
                        'path'    => request()?->path(),
                    ]);
                }
            });
        }

        // ── 2. Public layout: menu composer — cached 5 min ──────────
        view()->composer('layouts.app', function ($view) {
            $headerMenu = Cache::remember('menu_nav_header', 300, fn() =>
                \App\Models\Menu::where('location', 'header')
                    ->with(['items' => fn($q) => $q
                        ->whereNull('parent_id')
                        ->with(['children.page', 'page'])
                        ->orderBy('order')
                    ])->first()
            );

            $footerMenu = Cache::remember('menu_nav_footer', 300, fn() =>
                \App\Models\Menu::where('location', 'footer')
                    ->with(['items' => fn($q) => $q
                        ->whereNull('parent_id')
                        ->with(['children.page', 'page'])
                        ->orderBy('order')
                    ])->first()
            );

            $view->with('headerMenu', $headerMenu)->with('footerMenu', $footerMenu);
        });

        // ── 3. Global settings — ONE cache read per hour ─────────────
        try {
            $settings = Cache::remember('system_settings_global', 3600, function () {
                $all = SystemSetting::pluck('value', 'key')->toArray();
                return [
                    'timezone'          => $all['site_timezone']      ?? config('app.timezone', 'UTC'),
                    'currency'          => $all['site_currency']       ?? 'AED',
                    'currency_position' => $all['currency_position']   ?? 'before',
                    'date_format'       => $all['date_format']          ?? 'd/m/Y',
                    'site_name'         => $all['site_name']            ?? config('app.name'),
                    'site_logo'         => $all['site_logo']            ?? null,
                    'site_favicon'      => $all['site_favicon']         ?? null,
                ];
            });

            // Apply timezone
            if ($settings['timezone']) {
                Config::set('app.timezone', $settings['timezone']);
                date_default_timezone_set($settings['timezone']);
            }

            // Share to all views — single operation
            View::share([
                'appCurrencyCode'   => $settings['currency'],
                'appCurrencySymbol' => \App\Helpers\CurrencyHelper::all()[$settings['currency']]['symbol'] ?? $settings['currency'],
                'appCurrencyPos'    => $settings['currency_position'],
                'appDateFormat'     => $settings['date_format'],
                'adminSiteName'     => $settings['site_name'],
                'adminSiteLogo'     => $settings['site_logo'],
                'adminSiteFavicon'  => $settings['site_favicon'],
            ]);

        } catch (\Exception $e) {
            // DB not ready yet — share safe defaults
            View::share([
                'appCurrencyCode'   => 'AED',
                'appCurrencySymbol' => 'AED',
                'appCurrencyPos'    => 'before',
                'appDateFormat'     => 'd/m/Y',
                'adminSiteName'     => config('app.name', 'Motor Bazar'),
                'adminSiteLogo'     => null,
                'adminSiteFavicon'  => null,
            ]);
        }
    }
}
