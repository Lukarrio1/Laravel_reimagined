<?php

namespace App;

use App\Models\User;
use App\Models\TenantUser;
use App\Models\Tenant\Tenant;
use App\Models\Scopes\TenantScope;

trait TenantTrait
{
    public function initialize()
    {
        static::addGlobalScope(new TenantScope);
    }

    public function land_lord()
    {
        return $this->hasOneThrough(Tenant::class, TenantUser::class, 'user_id', 'id', 'id', 'tenant_id');
    }

    public function tenants(){
        return $this->hasManyThrough(User::class, TenantUser::class, 'tenant_id', 'id', 'id', 'user_id');
    }
}