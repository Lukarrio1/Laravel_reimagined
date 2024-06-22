<?php

namespace App\Providers;

use App\Models\User;
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
        Config::set('mail', $mail_config);
        (new User())->deleteInactiveUsers();
    }
}
