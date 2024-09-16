<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Setting;
use App\Models\Node\Node;
use App\Models\Tenant\Tenant;
use App\Models\ReferenceConfig;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\Reference\Reference;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Container\Attributes\Log;

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
            \strtolower('MAIL_HOST') =>  \optional($setting->where('key', 'mail_host')->first())->properties,
            \strtolower('MAIL_PORT') =>  \optional($setting->where('key', 'mail_port')->first())->properties,
            \strtolower('MAIL_USERNAME') =>  \optional($setting->where('key', 'mail_username')->first())->properties,
            \strtolower('MAIL_PASSWORD') =>  \optional($setting->where('key', 'mail_password')->first())->properties,
            \strtolower('MAIL_ENCRYPTION') =>  \optional($setting->where('key', 'mail_encryption')->first())->properties,
            \strtolower('MAIL_FROM_ADDRESS') => \optional($setting->where('key', 'mail_from_address')->first())->properties,
            \strtolower('MAIL_FROM_NAME') =>  \optional($setting->where('key', 'mail_from_name')->first())->properties,
            'mail_url' =>  \optional($setting->where('key', 'mail_url')->first())->properties,
            'transport' => \optional($setting->where('key', 'mail_mailer')->first())->properties
        ];
        Config::set('mail', $mail_config);

        if (!Cache::has('roles')) {
            Cache::set('roles', Role::all()->pluck('id', 'name'));
        }

        Config::set('cache.default', \optional(Setting::where('key', 'cache_driver')->first())
            ->getSettingValue('last') ?? "redis");

        if (!Cache::has('setting_allowed_login_roles')) {
            $allowed_login_roles = \optional(Setting::where('key', 'allowed_login_roles')->first())->getSettingValue('last') ?? \collect([]);
            Cache::add('setting_allowed_login_roles', $allowed_login_roles->toArray());
        }

        if (!Cache::has('setting_databases')) {
            $databases = collect([]);
            $data_configurations = Cache::get('settings', collect([]))
                ->where('key', 'database_configuration')->first();
            $data_configurations = !empty($data_configurations) ? $data_configurations->getSettingValue() : [];
            collect($data_configurations)
                ->keys()
                ->each(fn($db) => $databases->put($db, $db));
            Cache::add('setting_databases', $databases);
        }
        if (!Cache::has('setting_backup_databases')) {
            $item =  collect(Cache::get('settings'))
                ->where('key', 'database_backup_configuration')->first() ?? [];
            if (!empty($item)) {
                $item = gettype($item) == "array" ? [] : $item->getSettingValue()->toArray();
            }

            Cache::add('setting_backup_databases', $item);
        }

        if (!Cache::has('routes')) {
            $nodes = Node::where('node_status', 1)
                ->where('node_type', 1)
                ->get();
            Cache::add('routes', $nodes);
        }

        if (!Cache::has('references')) {
            Cache::add('references', ReferenceConfig::query()
                ->whereIn('type', optional(collect(Cache::get('settings'))
                    ->where('key', 'reference_types')->first())->getSettingValue() ?? collect([]))
                ->distinct('type')
                ->get());
        }
        \collect(optional(collect(Cache::get('settings'))
            ->where('key', 'reference_types')->first())->getSettingValue())
            ->each(function ($ref) {
                $rel_type = collect(\explode('_', $ref));
                $ref = Cache::get('references')->where('type', $ref)->first();
                if ($rel_type->count() > 1 && !empty($ref)) {
                    $owned_model = $ref->owned_model;
                    $owner_model = $ref->owner_model;
                    $has_many = (int) $rel_type->last() == 1 ? "hasManyThrough" : "hasOneThrough";

                    // creates the owner relationships
                    $owner_model::resolveRelationUsing($rel_type->first(), function ($owner_model) use ($owned_model, $has_many, $ref) {
                        return $owner_model->$has_many($owned_model, Reference::class, 'owner_id', 'id', 'id', 'owned_id')
                            ->where('references.type', $ref->type);
                    });

                    // creates the reverse of the owned relationship
                    $owned_model::resolveRelationUsing($rel_type->first() . "_owner", function ($owner_model) use ($owned_model, $has_many, $ref) {
                        return $owner_model->$has_many($owned_model, Reference::class, 'owned_id', 'id', 'id', 'owner_id')
                            ->where('references.type', $ref->type);
                    });
                }
            });

        \collect(
            optional(collect(Cache::get('settings'))
                ->where('key', 'database_configuration')->first())
                ->getSettingValue()
        )
            ->each(function ($item, $key) {
                if (empty($item) || empty($key)) {
                    return false;
                }
                try {
                    Config::set("database.connections.{$key}", [
                        'driver'    => $item->get('DB_CONNECTION') ?? "mysql",
                        'host'      => $item->get('DB_HOST'),
                        'port'      => $item->get('DB_PORT'),
                        'database'  => $item->get('DB_DATABASE'),
                        'username'  => $item->get('DB_USERNAME'),
                        'password'  => $item->get('DB_PASSWORD'),
                        'charset'   => 'utf8',
                        'collation' => 'utf8_unicode_ci',
                        'prefix'    => '',
                    ]);
                } catch (\Throwable $th) {
                    //throw $th;

                }
            });


        (new User())->deleteInactiveUsers();
    }
}
