<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reference extends Model
{
    use HasFactory;
    protected $fillable = ["owner_id","owner_model","owned_model","owned_id","type"];
}
