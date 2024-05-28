<?php

namespace App\Models\Tenant;

use App\Models\User;
use App\TenantTrait;
use App\Models\TenantUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{

    use HasFactory;
    use TenantTrait;

    protected $guarded = ['id'];
    protected $appends = ['api_base_url'];

    public function getStatusAttribute($value)
    {
        return ['value' => $value, 'human_value' => [0 => "In Active", 1 => "Active"][$value]];
    }


    public function getApiBaseUrlAttribute(){
        // return
    }
}
