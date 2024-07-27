<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Models\Reference\Reference;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function profile()
    {

        $user = request()->user()->load(['friends.friend','roles.permissions' => fn ($q) => $q->select('id')]);

        return \response()->json(['user' => $user]);
    }
}
