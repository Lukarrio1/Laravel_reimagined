<?php

namespace App\Http\Controllers\Api\Node;

use PSpell\Config;
use App\Models\User;
use App\Models\Node\Node;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Api\DataBusController;

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
            Cache::set('auth_user_permissions_' . $id, $permission_ids, $this->cache_ttl);
        }
        $nodes = collect([]);
        $cache_name = 'auth_nodes_user_' . $id;
        if (!Cache::has($cache_name)) {
            $nodes = Node::where('node_status', 1)
                ->select('name', 'properties', 'node_type', 'authentication_level', 'permission_id', 'id', 'uuid', 'verbiage')
                ->with(['permission'])
                ->get()
                ->filter(function ($node) {
                    if ($node->node_type['value'] == 1) {
                        if (isset($node->properties['value']->node_database) || isset($node->properties['value']->node_endpoint_to_consume)) {
                            return false;
                        }
                        return true;
                    }
                    return true;
                })
                ->map(function ($node) {
                    $node->hasAccess = $node->authentication_level['value'] == 0 ||
                        !empty($node->permission) && !\auth()->user()->hasPermissionTo(\optional($node->permission)->name) ? false : true;
                    $node = (object)[...$node->toArray(), 'properties' => ['value' => $this->removeKeys($node->properties['value'])]];
                    return $node;
                });
            Cache::set($cache_name, $nodes, $this->cache_ttl);
        } else {
            $nodes = Cache::get($cache_name);
        }
        return ['nodes' => $nodes->toArray()];
    }

    public function guest_nodes()
    {
        $nodes = collect([]);
        if (!Cache::has('guest_nodes')) {
            $nodes = Node::where('node_status', 1)
                ->select('name', 'properties', 'node_type', 'authentication_level', 'permission_id', 'id', 'uuid', 'verbiage')
                ->with(['permission'])
                ->get()
                ->filter(function ($node) {
                    if ($node->node_type['value'] == 1) {
                        if (isset($node->properties['value']->node_database) || isset($node->properties['value']->node_endpoint_to_consume)) {
                            return false;
                        }
                        return true;
                    }
                    return true;
                })
                ->map(function ($node) {
                    $node->hasAccess = !empty($node->permission_id) ||  $node->authentication_level['value'] == 1 ? false : true;
                    $node = (object)[...$node->toArray(), 'properties' => ['value' => $this->removeKeys($node->properties['value'])]];
                    return $node;
                });
            Cache::set('guest_nodes', $nodes);
        } else {
            $nodes = Cache::get('guest_nodes');
        }
        return ['nodes' => $nodes->toArray()];
    }


    // public function unauthNodes()
    // {
    //     $nodes = Node::where('node_type', '>', 1)
    //         ->whereIn('authentication_level', [0, 2])
    //         ->where(
    //             'node_status',
    //             1
    //         )
    //         ->select('name', 'properties', 'node_type', 'authentication_level', 'permission_id', 'id', 'uuid', 'verbiage')
    //         ->get()->map(function ($node) {
    //             $node->hasAccess = true;
    //             $node = (object)[...$node->toArray(), 'properties' => ['value' => $this->removeKeys($node->properties['value'])]];
    //             return $node;
    //         });

    //     return ['nodes' => $nodes];
    // }
}
