<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Audit;
use App\Models\Node\Node;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $take=10;
        $users = User::query();
        $nodes = Node::query();
        $audits = Audit::query();
        return \view('Dashboard.View', [
            'new_users' => $users->latest()->take($take)->get(),
            'last_used_routes' => $nodes->where('node_type', 1)->orderBy('updated_at', 'desc')->take($take)->get(),
            'audit_history' => $audits->latest('created_at')->take($take)->get()
        ]);
    }
}
