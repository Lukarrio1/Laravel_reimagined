<?php

namespace App\Models;

use App\Models\Reference\Reference;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BaseModel extends Model
{
    use HasFactory;
    use Cachable;

    public function references()
    {
        return $this->hasMany(Reference::class, 'owner_id', 'id');
    }

    public function createReference($type, $owner_id, $owned_id)
    {
        if (empty($type) || empty($owned_id) || empty($owned_id)) {
            return null;
        }
        $config =  ReferenceConfig::where('type', $type)->first();

        if (empty($config)) {
            return null;
        }
        $Reference = Reference::updateOrCreate([
            "owner_id" => $owner_id,
            "owner_model" => $config->owner_model,
            "owned_model" => $config->owned_model,
            "owned_id" => $owned_id,
            "type" => $config->type,
        ], [
            "owner_id" => $owner_id,
            "owner_model" => $config->owner_model,
            "owned_model" => $config->owned_model,
            "owned_id" => $owned_id,
            "type" => $config->type,
        ]);
        return $Reference;
    }

    public function deleteReference($type, $owner_id, $owned_id)
    {
        if (empty($type) || empty($owned_id)) {
            return null;
        }
        $config =  ReferenceConfig::where('type', $type)->first();

        if (empty($config)) {
            return null;
        }
        $owner_id_query = $owner_id != null ? ["owner_id" => $owner_id] : [];
        Reference::where($owner_id_query + [
            "owner_model" => $config->owner_model,
            "owned_model" => $config->owned_model,
            "owned_id" => $owned_id,
            "type" => $config->type,
        ])->delete();
        return true;
    }
}
