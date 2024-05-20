<?php

namespace App\Http\Controllers\Api\Node;

use App\Models\User;
use App\Models\Node\Node;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class NodeController extends Controller
{
    public function node($uuid)
    {
        return ['node' => Node::where('uuid', $uuid)->first()];
    }

    public function nodes()
    {
        $id = request()->user()->id;

        $permission_ids  = \collect([]);
        $user = User::with('roles.permissions')->find($id);
        if (Cache::has('auth_user_permissions_' . $id)) {
            $permission_ids = Cache::get('auth_user_permissions_' . $id);
        } else {
            \collect($user->roles)->each(function ($role) use ($permission_ids) {
                \collect($role->permissions)->each(function ($permission) use ($permission_ids) {
                    $permission_ids->push($permission->id);
                });
            });
            Cache::set('auth_user_permissions_' . $id, $permission_ids);
        }

        $nodes = Node::where('node_type', '>', 1)
            ->where('node_status', 1)
            ->select('name', 'properties', 'node_type', 'authentication_level', 'permission_id', 'id')
            ->with(['permission'])
            ->get()
            ->map(function ($node) use ($permission_ids) {
                if ($node->node_type['value'] == 3) {
                    $node->hasAccess = empty($node->permission_id) ? true : \in_array($node->permission_id, $permission_ids->toArray());
                }
                return $node;
            });
        return ['nodes' => $nodes];
    }


    public function unauthNodes()
    {
        $nodes = Node::where('node_type', '>', 1)
            ->whereIn('authentication_level', [0, 2])
            ->where(
                'node_status',
                1
            )
            ->select('name', 'properties', 'node_type', 'authentication_level', 'permission_id', 'id')
            ->get()->map(function ($node) {
                $node->hasAccess = true;
                return $node;
            });

        return ['nodes' => $nodes];
    }
}
