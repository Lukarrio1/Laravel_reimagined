<?php

namespace App\Http\Controllers\Api\Node;

use App\Models\Node\Node;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NodeController extends Controller
{
    public function nodes($uuid){
        return ['node'=>Node::whereUuid($uuid)->first()];
    }
}
