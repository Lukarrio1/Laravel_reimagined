<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Abis extends BaseModel
{
    use HasFactory;

    protected $fillable = ['abis_number', 'user_id'];
}
