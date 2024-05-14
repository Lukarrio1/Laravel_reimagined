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

    protected $guarded =['id'];


}
