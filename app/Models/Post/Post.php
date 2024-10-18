<?php

namespace App\Models\Post;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends BaseModel
{
    use HasFactory;
    protected $fillable = ['title', 'body', 'is_active'];
}
