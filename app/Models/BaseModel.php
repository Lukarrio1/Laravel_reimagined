<?php

namespace App\Models;

use App\Models\Reference\Reference;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BaseModel extends Model
{
    use HasFactory;

    public function references()
    {
        return $this->hasMany(Reference::class, 'owner_id', 'id');
    }

    public function createReference($type = '', $owner_id, $owned_id)
    {
        //
        $config =  ReferenceConfig::where('type', $type)->first();
        $Reference = Reference::create([
            "owner_id" => $owner_id,
             "owner_model" => $config->owner_model,
              "owned_model" => $config->owned_model,
               "owned_id" => $owned_id,
                "type" => $config->type,
        ]);
        return $Reference;

    }


}
