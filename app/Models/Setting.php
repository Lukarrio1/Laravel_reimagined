<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function SETTING_OPTIONS($key, $value)
    {
        $html = '';
        $value = collect($value);
        switch ($key) {
            case 'drop_down':
                $value->each(function($key,$val) use(&$html){
                    $html .="<option value='".$val."_".$key."'>$val</option>";
                });
                $html="<select class='form-select' name='value'>$html</select>";
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
                'field' => $this->SETTING_OPTIONS('drop_down', Role::all()->pluck('id', 'name')),
                'handle'=>['action'=>'split','value'=>'last']
            ],
        ]);
        return $keys->get($key);
    }

    public function getSettingValue(){
        $key = $this->SETTING_KEYS($this->key)['handle'];
        $value ='';
        switch ($key['action']) {
            case 'split':
                $value =$key['value'];
                $value =\explode('_',$this->properties)[$value=='last'?count(explode('_',$this->properties))-1:0];
                break;

            default:
                # code...
                break;
        }
        return $value;
    }

    public function getAllSettingKeys($key=""){
        $keys =['admin_role'=>"Admin Role"];
        return $key ? $keys[$key]:$keys;
    }
}
