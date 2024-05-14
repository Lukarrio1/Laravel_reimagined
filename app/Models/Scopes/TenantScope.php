<?php

namespace App\Models\Scopes;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {

        $tenantId = config('tenant_id');

        $multi_tenancy = optional(collect(Cache::get('settings'))
                ->where('key', 'multi_tenancy')->first(null,'false'))
            ->getSettingValue('last');

        if ($multi_tenancy == 'true') {
            $builder->whereHas('land_lord',fn($q)=>$q->where('tenant_id', $tenantId));
        }

    }
}
