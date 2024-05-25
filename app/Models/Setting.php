<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


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
        // \optional(self::where('key',$setting_key)->first())->getSettingValue();
        $value = !empty($prev_val) ? $prev_val : collect($value);
        switch ($key) {
            case 'drop_down':
                $value->each(function ($key, $val) use (&$html) {
                    $html .= "<option value='" . $val . "_" . $key . "'>$val</option>";
                });
                $html = "<select class='form-select' name='value'>$html</select>";
                break;
            case 'input':
                $html = "<input class='form-control' name='value' value='" . $value->join('') . "'>";
                break;

            default:
                # code...
                break;
        }

        return $html;
    }

    public function SETTING_KEYS($key)
    {
        $keys = collect([
            'admin_role' => [
                'field' => $this->SETTING_OPTIONS('drop_down', Role::all()->pluck('id', 'name'), $key),
                'handle' => ['action' => 'split', 'value' => 'last'],
            ],
            'registration_role' => [
                'field' => $this->SETTING_OPTIONS('drop_down', Role::all()->pluck('id', 'name'), $key),
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

            default:
                $value = $this->properties;
                break;
        }
        return $value;
    }

    public function getAllSettingKeys($key = "")
    {
        $keys = [
            'admin_role' => "Super Admin Role",
            'registration_role' => 'Api Registration Role',
            'app_name' => 'Application Name',
            'app_url' => 'Application URL',
            'app_version' => 'Application Version',
            'app_animation' => 'Application Animation',
            // 'multi_tenancy' => 'Api Multi Tenancy',
            \strtolower('MAIL_MAILER') => 'Mail Mailer',
            \strtolower('MAIL_HOST') => 'Mail Host',
            \strtolower('MAIL_PORT') => 'Mail Port',
            \strtolower('MAIL_USERNAME') => 'Mail Username',
            \strtolower('MAIL_PASSWORD') => 'Mail Password',
            \strtolower('MAIL_ENCRYPTION') => 'Mail Encryption',
            \strtolower('MAIL_FROM_ADDRESS') => 'Mail Form Address',
            \strtolower('MAIL_FROM_NAME') => 'Mail From Name',
            'mail_url' => "Mail Url",
            "app_auditing" => "Application Auditing"

        ];
        return $key ? $keys[$key] : $keys;
    }
}
