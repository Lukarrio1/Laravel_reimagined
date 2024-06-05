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
        $id = request()->user()->id ?? null;

        $permission_ids  = \collect([]);
        $user = User::with('roles.permissions')->find($id);
        if (Cache::has('auth_user_permissions_' . $id)) {
            $permission_ids = Cache::get('auth_user_permissions_' . $id);
        } else {
            \collect(\collect($user)->get('roles', []))->each(function ($role) use ($permission_ids) {
                \collect(\collect($role)->get('permissions', []))->each(function ($permission) use ($permission_ids) {
                    $permission_ids->push(\optional($permission)->id);
                });
            });
            Cache::set('auth_user_permissions_' . $id, $permission_ids);
        }


        $nodes = Node::where('node_status', 1)
            ->select('name', 'properties', 'node_type', 'authentication_level', 'permission_id', 'id', 'uuid')
            ->with(['permission'])
            ->get()
            ->map(function ($node) use ($permission_ids) {
                $node->hasAccess = true;
                if (
                    empty(\request()->user()) && $node->authentication_level['value'] == 1 ||
                    !empty(\request()->user()) && $node->authentication_level['value'] == 0 ||
                    !empty($node->permission_id) && !\in_array($node->permission_id, $permission_ids->toArray())
                ) {
                    $node->hasAccess = false;
                }
                return $node;
            });
        return ['nodes' => $nodes];
    }

    public function guest_nodes()
    {

        $nodes = Node::where('node_status', 1)
            ->select('name', 'properties', 'node_type', 'authentication_level', 'permission_id', 'id', 'uuid')
            ->with(['permission'])
            ->get()
            ->map(function ($node) {
                $node->hasAccess = true;
                $node->hasAccess = !empty($node->permission_id) ||  $node->authentication_level['value'] == 1 ? false : true;
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
            ->select('name', 'properties', 'node_type', 'authentication_level', 'permission_id', 'id', 'uuid')
            ->get()->map(function ($node) {
                $node->hasAccess = true;
                return $node;
            });

        return ['nodes' => $nodes];
    }
}
