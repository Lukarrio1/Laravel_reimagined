<?php

namespace App\Models;

use App\TenantTrait;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    use TenantTrait;
    public function __construct()
    {
        // $this->initializeTenancy();
    }

    public const ANIMATIONS = [
        // 'w3-animate-fading' => 'w3-animate-fading',
        'w3-animate-zoom' => 'w3-animate-zoom',
        'w3-animate-opacity' => 'w3-animate-opacity',
        'w3-animate-right' => 'w3-animate-right',
        'w3-animate-left' => 'w3-animate-left',
        'w3-animate-bottom' => 'w3-animate-bottom',
        'w3-animate-top' =>  'w3-animate-top'
    ];

    public function SETTING_OPTIONS($key, $value, $setting_key)
    {
        $html = '';
        $prev_val = '';
        $field_value = self::where('key', $setting_key)->first()->properties ?? '';
        $value = !empty($prev_val) ? $prev_val : collect($value);
        switch ($key) {
            case 'drop_down':
                $value->each(function ($key, $val) use (&$html, $field_value) {
                    $selected = $field_value == $val . "_" . $key ? "selected" : '';
                    $html .= "<option value='" . $val . "_" . $key . "' $selected>$val</option>";
                });
                $html = "<select class='form-select' name='value'>$html</select>";
                break;
            case 'multi_select':
                $value->each(function ($key, $val) use (&$html, $field_value) {
                    $selected = \in_array($key, Cache::get('setting_allowed_login_roles', [])) ? "selected" : '';
                    $html .= "<option value='" . $val . "_" . $key . "' $selected>$val</option>";
                });
                $html = "<select class='form-select' name='value[]' multiple>$html</select>";
                break;
            case 'input':
                $html = "<input class='form-control' name='value' value='" . $field_value . "'>";
                break;
            default:
                # code...
                break;
        }

        return $html;
    }

    public function SETTING_KEYS($key)
    {
        $roles = Role::all()->pluck('id', 'name');
        $keys = collect([
            'admin_role' => [
                'field' => $this->SETTING_OPTIONS('drop_down', $roles, $key),
                'handle' => ['action' => 'split', 'value' => 'last'],
            ],
            'registration_role' => [
                'field' => $this->SETTING_OPTIONS('drop_down', $roles, $key),
                'handle' => ['action' => 'split', 'value' => 'last'],
            ],
            'app_name' => [
                'field' => $this->SETTING_OPTIONS('input', '', $key),
                'handle' => ['action' => '', 'value' => ''],
            ],
            \strtolower('MAIL_MAILER') => [
                'field' => $this->SETTING_OPTIONS('input', '', $key),
                'handle' => ['action' => '', 'value' => ''],
            ],
            \strtolower('MAIL_HOST') => [
                'field' => $this->SETTING_OPTIONS('input', '', $key),
                'handle' => ['action' => '', 'value' => ''],
            ],
            \strtolower('MAIL_PORT') => [
                'field' => $this->SETTING_OPTIONS('input', '', $key),
                'handle' => ['action' => '', 'value' => ''],
            ],
            \strtolower('MAIL_USERNAME') => [
                'field' => $this->SETTING_OPTIONS('input', '', $key),
                'handle' => ['action' => '', 'value' => ''],
            ],
            \strtolower('MAIL_PASSWORD') => [
                'field' => $this->SETTING_OPTIONS('input', '', $key),
                'handle' => ['action' => '', 'value' => ''],
            ],
            \strtolower('MAIL_ENCRYPTION') => [
                'field' => $this->SETTING_OPTIONS('input', '', $key),
                'handle' => ['action' => '', 'value' => ''],
            ],
            \strtolower('MAIL_FROM_ADDRESS') => [
                'field' => $this->SETTING_OPTIONS('input', '', $key),
                'handle' => ['action' => '', 'value' => ''],
            ],
            \strtolower('MAIL_FROM_NAME') => [
                'field' => $this->SETTING_OPTIONS('input', '', $key),
                'handle' => ['action' => '', 'value' => ''],
            ],
            'multi_tenancy' => [
                'field' => $this->SETTING_OPTIONS('drop_down', [true => 'true', false => 'false'], $key),
                'handle' => ['action' => 'split', 'value' => 'first'],
            ],
            'multi_tenancy_role' => [
                'field' => $this->SETTING_OPTIONS('drop_down', $roles, $key),
                'handle' => ['action' => 'split', 'value' => 'last'],
            ],
            'mail_url' => [
                'field' => $this->SETTING_OPTIONS('input', '', $key),
                'handle' => ['action' => '', 'value' => ''],
            ],
            'app_url' => [
                'field' => $this->SETTING_OPTIONS('input', '', $key),
                'handle' => ['action' => '', 'value' => ''],
            ],
            'app_version' => [
                'field' => $this->SETTING_OPTIONS('input', '', $key),
                'handle' => ['action' => '', 'value' => ''],
            ],
            'app_animation' => [
                'field' => $this->SETTING_OPTIONS('drop_down', self::ANIMATIONS, $key),
                'handle' => ['action' => 'split', 'value' => 'last'],
            ],
            'app_auditing' => [
                'field' => $this->SETTING_OPTIONS('drop_down', ['true' => true, 'false' => false], $key),
                'handle' => ['action' => 'split', 'value' => 'last'],
            ],
            'allowed_login_roles' => [
                'field' => $this->SETTING_OPTIONS('multi_select', $roles, $key),
                'handle' => ['action' => 'multi_split', 'value' => 'last'],
            ],
        ]);
        return $keys->get($key);
    }

    public function getSettingValue($value = '')
    {
        $key = $this->SETTING_KEYS($this->key)['handle'];
        switch ($key['action']) {
            case 'split':
                $value = !empty($value) ? $value : $key['value'];
                $value = \explode('_', $this->properties)[$value == 'last' ? count(explode('_', $this->properties)) - 1 : 0];
                break;
            case 'multi_split':
                $value = !empty($value) ? $value : $key['value'];
                $value = $value == 'first' ? "<ul class='list-group list-group-flush'>" . collect(\explode('|', $this->properties))
                    ->map(fn ($item) => \collect(\explode('_', $item))
                        ->filter(fn ($item, $idx) =>  $idx == 0)->map(fn ($item) => \collect(\explode('--', $item))->join(' ')))
                    ->flatten()->map(fn ($item) => "<li class='list-group-item'>" . $item . "</li>")->join('') . "</ul>" :
                    collect(\explode('|', $this->properties))->map(fn ($item) => \collect(\explode('_', $item))
                        ->filter(fn ($item, $idx) =>  $idx > 0))->flatten();
                break;
            default:
                $value = $this->properties;
                break;
        }
        return $value;
    }

    public function getAllSettingKeys($key = "")
    {

        $multi_tenancy = (int)optional(collect(Cache::get('settings'))->where('key', 'multi_tenancy')->first())
            ->getSettingValue('first');
        $keys = \collect([
            'admin_role' => "Super Admin Role",
            'registration_role' => 'Api Registration Role',
            'allowed_login_roles' => "Allowed Login Roles",
            'app_name' => 'Application Name',
            'app_url' => 'Application URL',
            'app_version' => 'Application Version',
            'app_animation' => 'Application Animation',
            'multi_tenancy' => 'Api Multi Tenancy',
            "multi_tenancy_role" => "Api Multi Tenancy Role",
            "app_auditing" => "Application Auditing",
            \strtolower('MAIL_MAILER') => 'Mail Mailer',
            \strtolower('MAIL_HOST') => 'Mail Host',
            \strtolower('MAIL_PORT') => 'Mail Port',
            \strtolower('MAIL_USERNAME') => 'Mail Username',
            \strtolower('MAIL_PASSWORD') => 'Mail Password',
            \strtolower('MAIL_ENCRYPTION') => 'Mail Encryption',
            \strtolower('MAIL_FROM_ADDRESS') => 'Mail Form Address',
            \strtolower('MAIL_FROM_NAME') => 'Mail From Name',
            'mail_url' => "Mail Url",
        ])->when($multi_tenancy == 0, function ($collection) {
            return $collection->filter((function ($item, $key) {
                return $key != "multi_tenancy_role";
            }));
        });
        return $key ? $keys->get($key) : $keys->toArray();
    }
}
