<?php

namespace App\Http\Controllers\Api\Node;

use App\Models\Node\Node;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NodeController extends Controller
{
    public function nodes(){
        return ['nodes'=>Node::all()];
    }
}
