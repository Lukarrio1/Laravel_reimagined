<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Setting;
use App\Models\Node\Node;
use App\Models\Tenant\Tenant;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
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
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        if (!Cache::has('settings')) {
            Cache::add('settings', Setting::all());
        }
        if (!Cache::has('redirect_to_options')) {
            $links =  Node::query()->where('node_type', 2)->get()->map(function ($item) {
                $temp = \collect([]);
                $temp->put('name', $item->name);
                $temp->put('route', $item->properties['value']->node_route);
                return $temp->toArray();
            })->pluck('route', 'name');
            Cache::add('redirect_to_options', $links);
        }

        $setting = collect(Cache::get('settings'));
        $mail_config = [
            \strtolower('MAIL_MAILER') => \optional($setting->where('key', 'mail_mailer')->first())->properties ?? "mailtrap",
            \strtolower('MAIL_HOST') =>  \optional($setting->where('key', 'mail_host')->first())->properties,
            \strtolower('MAIL_PORT') =>  \optional($setting->where('key', 'mail_port')->first())->properties,
            \strtolower('MAIL_USERNAME') =>  \optional($setting->where('key', 'mail_username')->first())->properties,
            \strtolower('MAIL_PASSWORD') =>  \optional($setting->where('key', 'mail_password')->first())->properties,
            \strtolower('MAIL_ENCRYPTION') =>  \optional($setting->where('key', 'mail_encryption')->first())->properties,
            \strtolower('MAIL_FROM_ADDRESS') => \optional($setting->where('key', 'mail_from_address')->first())->properties,
            \strtolower('MAIL_FROM_NAME') =>  \optional($setting->where('key', 'mail_from_name')->first())->properties,
            'mail_url' =>  \optional($setting->where('key', 'mail_url')->first())->properties,
        ];
        if (!Cache::has('roles')) {
            Cache::set('roles', Role::all()->pluck('id', 'name'));
        }

        Config::set('mail', $mail_config);

        if (Cache::has('settings')) {
            Config::set('cache.default', \optional($setting->where('key', 'cache_driver')->first())->getSettingValue('last'));
        }
        if (!Cache::has('setting_allowed_login_roles')) {
            $allowed_login_roles = \optional(Setting::where('key', 'allowed_login_roles')->first())->getSettingValue('last') ?? \collect([]);
            Cache::add('setting_allowed_login_roles', $allowed_login_roles->toArray());
        }
        if (!Cache::has('not_exportable_tables')) {
            Cache::add(
                'not_exportable_tables',
                \collect(\explode('|', \optional(Setting::where('key', 'not_exportable_tables')->first())->properties))
                    ->map(fn ($item_1) => \collect(\explode('_', $item_1))
                        ->filter(fn ($item_2, $idx) => $idx < (count(\explode('_', $item_1))) - 1)->join("_")) ?? \collect([])
            );
        }

        if (!Cache::has('routes')) {
            $nodes = Node::where('node_status', 1)
                ->where('node_type', 1)
                ->get();
            // Add routes to cache
            Cache::add('routes', $nodes); // Cache with expiration (optional)
        }
        if (!Cache::has('tenants')) {
            Cache::add('tenants', Tenant::all());
        }
    }
}
